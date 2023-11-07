<?php

namespace App\Infrastructure\Event;

use DateTimeImmutable;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqMessageProducer extends RabbitMqMessaging
{
    /**
     * @param $exchangeName
     * @param $notificationMessage
     * @param $notificationType
     * @param $notificationId
     * @param DateTimeImmutable $notificationOccurredOn
     * @param string $type
     * @param string $queuename
     * @param string $routing_key
     */
    public function send(
        $exchangeName,
        $notificationMessage,
        $notificationType,
        $notificationId,
        DateTimeImmutable $notificationOccurredOn,
        $type = 'fanout',
        $queuename = '',
        $routing_key = ''
    ) {
        $this->channel($exchangeName, $type, $queuename)->basic_publish(
            new AMQPMessage(
                $notificationMessage,
                [
                    'type'       => $notificationType,
                    'timestamp'  => $notificationOccurredOn->getTimestamp(),
                    'message_id' => $notificationId
                ]
            ),
            $exchangeName,
            $routing_key
        );
    }
}
