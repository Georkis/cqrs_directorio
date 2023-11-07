<?php

namespace App\Application\Service\Event\EventConsumer;

class EventConsumerCommand
{

    private string $exchangeName;
    private string $queueName;

    public function __construct(
        string $exchangeName,
        string $queueName
    ) {
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    public function queueName(): string
    {
        return $this->queueName;
    }

}
