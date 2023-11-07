<?php

namespace App\Application\Service\Event\EventConsumer;

use App\Application\EventConsumerLoggerInterface;
use ErrorException;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Process\Process;

class EventConsumer
{

    const MAX_REQUEUE = 10;

    protected AMQPStreamConnection $connection;
    protected AMQPChannel $channel;
    protected bool $error = false;
    protected string $exchange;
    protected string $queue;
    protected EventConsumerLoggerInterface $eventConsumerLogger;
    private string $phpExecutable;
    private string $consoleBinPath;
    protected string $errorsExchange;
    protected string $errorQueue;

    public function __construct(
        string $phpExecutable,
        string $consoleBinPath,
        AMQPStreamConnection $connection,
        EventConsumerLoggerInterface $eventConsumerLogger,
    )
    {
        $this->connection = $connection;
        $this->eventConsumerLogger = $eventConsumerLogger;
        $this->phpExecutable = $phpExecutable;
        $this->consoleBinPath = $consoleBinPath;
    }

    /**
     * @param EventConsumerCommand $command
     * @throws ErrorException
     */
    public function handle(EventConsumerCommand $command)
    {
        set_time_limit(0);

        $this->exchange = $command->exchangeName();
        $this->queue = $command->queueName();

        $this->errorsExchange = "errors-" . $this->exchange;
        $this->errorQueue = "errors-" . $this->queue;

        $this->connect();

        $this->wait();
    }

    public function connect()
    {
        $this->connection->reconnect();

        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare(
            exchange: $this->exchange,
            type: 'fanout',
            passive: false,
            durable: true,
            auto_delete: false,
            internal: false,
            nowait: false,
            arguments: array(),
            ticket: null
        );

        $this->channel->queue_declare(
            queue: $this->queue,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );

        $this->channel->exchange_declare(
            exchange: $this->errorsExchange,
            type: 'fanout',
            passive: false,
            durable: true,
            auto_delete: false,
            internal: false,
            nowait: false,
            arguments: array(),
            ticket: null
        );

        $this->channel->queue_declare(
            queue: $this->errorQueue,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );

        $this->channel->queue_bind($this->errorsExchange, $this->errorQueue);
    }

    /**
     * @throws ErrorException
     * @throws Exception
     */
    public function wait()
    {
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $this->channel->basic_qos(
            prefetch_size: null,
            prefetch_count: 1,
            a_global: null
        );

        $this->channel->basic_consume(
            queue: $this->exchange,
            consumer_tag: '',
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: [$this, 'callback']
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        if ($this->error) {
            echo " [*] Reconnecting...\n";
            sleep(5);
            $this->error = false;
            $this->connect();
            $this->wait();
        } else {
            $this->channel->close();
            $this->connection->close();
        }
    }

    /**
     * @param $msg
     * @throws Exception
     */
    public function callback($msg)
    {
        echo ' [x] Received ' . $msg->get('type') . "\n";

        $process = new Process(
            [
                $this->phpExecutable,
                $this->consoleBinPath,
                'event:handle',
                $this->exchange,
                $msg->get('type'),
                $msg->get('timestamp'),
                $msg->body
            ]
        );

        $process->setTimeout(0);

        echo ' [x] Command: ' . $process->getCommandLine() . "\n";

        $this->eventConsumerLogger->whenProcessStart($this->exchange, $msg->body, $process);

        $process->run();

        echo ' [x] Exit code: ' . $process->getExitCode() . "\n";
        echo ' [x] Exit message: ' . $process->getExitCodeText() . "\n";

        // executes after the command finishes
        if ($process->isSuccessful()) {
            echo " [x] Done\n";
            //echo " Output: " . $process->getOutput() . "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        } else {
            echo " [x] Output: " . $process->getOutput() . "\n";
            echo " [x] Error: " . $process->getErrorOutput() . "\n";

            $this->processError($msg);

            $this->error = true;
            $this->channel->close();
            $this->connection->close();
        }
    }


    protected function processError($msg)
    {
        $body = json_decode($msg->body);
        $requeueCount = !empty($body->x_requeue_count) ? $body->x_requeue_count : 0;

        /** @var AMQPChannel $channel */
        $channel = $msg->delivery_info['channel'];

        $channel->basic_reject($msg->getDeliveryTag(), $requeue = false);

        if ($requeueCount >= self::MAX_REQUEUE) {
            echo " [x] MAX REQUEUE COUNT. REMOVING..." . "\n";
            $channel->basic_publish(
                new AMQPMessage(
                    json_encode($body),
                    [
                        'type' => $msg->get('type'),
                        'timestamp' => $msg->get('timestamp'),
                        'message_id' => $msg->get('message_id')
                    ]
                ),
                $this->errorsExchange,
                $this->errorQueue
            );
            return;
        }

        echo " [x] Requeuing: " . json_encode($body) . PHP_EOL;

        $requeueCount++;
        $body->{'x_requeue_count'} = $requeueCount;

        $channel->basic_publish(
            new AMQPMessage(
                json_encode($body),
                [
                    'type' => $msg->get('type'),
                    'timestamp' => $msg->get('timestamp'),
                    'message_id' => $msg->get('message_id')
                ]
            ),
            $this->exchange,
            $this->exchange
        );
    }
}
