<?php

namespace App\Application\Service\Event\EventProducer;

class EventProducerCommand
{
    private string $exchangeName;

    public function __construct(
        string $exchangeName
    ) {
        $this->exchangeName = $exchangeName;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

}
