<?php

namespace App\Infrastructure\Event;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMqMessaging
{
    protected AMQPStreamConnection $connection;
    protected AMQPChannel|null $channel;

    public function __construct(AMQPStreamConnection $aConnection)
    {
        $this->connection = $aConnection;
        $this->channel = null;
    }

    private function connect($exchangeName, $type = 'fanout', $queueName = '')
    {
        if (null !== $this->channel) {
            return;
        }

        $channel = $this->connection->channel();
        $channel->exchange_declare($exchangeName, $type, false, true, false);
        $channel->queue_declare($queueName, false, true, false, false);

        $channel->queue_bind($exchangeName, $exchangeName);

        $this->channel = $channel;
    }

    public function open($exchangeName)
    {

    }

    protected function channel($exchangeName, $type = 'fanout', $queueName = '')
    {
        $this->connect($exchangeName, $type, $queueName);

        return $this->channel;
    }

    public function close($exchangeName)
    {
        $this->channel->close();
        $this->connection->close();
    }
}
