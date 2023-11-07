<?php

namespace App\Domain\Repository\Event;

use App\Domain\Entity\Event\DomainEvent;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Entity\Event\ScheduleEvent;
use Doctrine\Common\Collections\Collection;

interface EventScheduleRepository
{
    public function save(ScheduleEvent $c);

    /**
     * @return ArrayCollection
     */
    public function getAllNeedToOccur(): ArrayCollection;

    public function getNotOccurredByEvent(DomainEvent $event): Collection;
}
