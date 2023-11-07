<?php

namespace App\Domain\Entity\Event;

use DateTimeImmutable;

abstract class AbstractDomainScheduleEvent implements DomainEvent
{
    protected DateTimeImmutable $willOccurOn;

    public function willOccurOn(): DateTimeImmutable
    {
        return $this->willOccurOn;
    }
}
