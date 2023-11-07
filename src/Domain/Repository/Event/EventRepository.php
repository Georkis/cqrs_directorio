<?php

namespace App\Domain\Repository\Event;

use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Entity\Event\Event;

interface EventRepository
{
    public function save(Event $c);

    /**
     * @param int $mostRecentPublishedMessageId
     * @return ArrayCollection
     */
    public function allStoredEventsSince(int $mostRecentPublishedMessageId, int $limit = 0, \DateTimeImmutable $endDate = null): ArrayCollection;

    /**
     * @param int $mostRecentPublishedMessageId
     * @param array $eventTypes
     * @param int $limit
     * @return ArrayCollection
     */
    public function allStoredEventSinceIn(int $mostRecentPublishedMessageId, array $eventTypes, int $limit = 0): ArrayCollection;

}
