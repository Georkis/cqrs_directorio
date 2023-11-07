<?php

namespace App\Infrastructure\Event\Producer;

abstract class AbstractProducerQueues
{
    abstract public function subscribedEvents(): array;
    abstract public function queueName(): string;
}
