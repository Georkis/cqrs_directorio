<?php

namespace App\Infrastructure\Event\Handler;


interface HandleEventsInterface
{

    public function subscribe(EventHandlerInterface $aDomainEventSubscriber);

    public function fire(
        string $type,
        object $body,
        string $occurredOn,
        string $exchange
    ): int;


}
