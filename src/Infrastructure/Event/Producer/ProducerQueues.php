<?php

namespace App\Infrastructure\Event\Producer;

class ProducerQueues
{
    protected array $events = [];

    public function __construct(iterable $producerEvents)
    {
        foreach ($producerEvents as $producerEvent) {
            $this->events[$producerEvent->queueName()] = $producerEvent->subscribedEvents();
        }
    }

    public function eventsByQueue(string $queue)
    {
        return !key_exists($queue, $this->events) ? [] : $this->events[$queue];
    }

}
