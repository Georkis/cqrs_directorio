<?php

namespace App\Domain\Entity\Event;

use DateTimeImmutable;

abstract class AbstractDomainEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
