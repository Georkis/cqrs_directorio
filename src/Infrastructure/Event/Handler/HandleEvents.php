<?php

namespace App\Infrastructure\Event\Handler;


class HandleEvents implements HandleEventsInterface
{
    /**
     * @var EventHandlerInterface[]
     */
    private array $subscribers = [];

    private int $id = 0;

    public function __construct(iterable $eventHandlers)
    {
        foreach ($eventHandlers as $eventHandler) {
            $this->subscribe($eventHandler);
        }
    }

    public function subscribe($aDomainEventSubscriber): int
    {
        $id = $this->id;
        $this->subscribers[$id] = $aDomainEventSubscriber;
        $this->id++;

        return $id;
    }

    public function fire(
        string $type,
        object $body,
        string $occurredOn,
        string $exchange
    ): int
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($exchange, $type)) {
                return $subscriber->fire($type, $body, $occurredOn);
            }
        }

        return 0;
    }

}
