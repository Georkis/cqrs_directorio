<?php

namespace App\Infrastructure\Event\Handler;


interface EventHandlerInterface
{
    public function fire(
        string $type,
        $body,
        string $occurredOn
    ): int;

    public function isSubscribedTo(string $exchange, string $type): bool;

}
