<?php

namespace App\Infrastructure\Repository\Event;

use App\Domain\Entity\Event\Event;
use App\Domain\Repository\Event\EventRepository;
use App\Infrastructure\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;

class DoctrineEventRepository extends AbstractDoctrineRepository implements EventRepository
{

    public function save(Event $c)
    {
        $this->em->persist($c);
    }

    public function allStoredEventsSince(int $mostRecentPublishedMessageId, int $limit = 0, \DateTimeImmutable $endDate = null): ArrayCollection
    {

        $dql = "SELECT c FROM Event:Event c WHERE c.id > :id";

        if ($endDate) {
            $dql .= " AND c.createdAt <= '" . $endDate->format("Y-m-d H:i:s") . "'";
        }

        $q = $this->em
            ->createQuery($dql)
            ->setParameters(
                [
                    'id' => $mostRecentPublishedMessageId,
                ]);


        if ($limit > 0) {
            $q = $q->setMaxResults($limit);
        }

        return new ArrayCollection(
            $q->getResult()
        );
    }

    /**
     * @inheritDoc
     */
    public function allStoredEventSinceIn(int $mostRecentPublishedMessageId, array $eventTypes, int $limit = 0): ArrayCollection
    {
        $q = $this->em
            ->createQuery("SELECT c FROM Event:Event c WHERE c.id > :id and c.type in (:events)")
            ->setParameters(
                [
                    'id' => $mostRecentPublishedMessageId,
                    'events' => $eventTypes
                ]);

        if ($limit > 0) {
            $q = $q->setMaxResults($limit);
        }

        return new ArrayCollection(
            $q->getResult()
        );
    }
}
