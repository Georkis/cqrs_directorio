<?php

namespace App\Infrastructure\Repository\Event;

use App\Domain\Entity\Event\DomainEvent;
use App\Domain\Entity\Event\ScheduleEvent;
use App\Domain\Repository\Event\EventScheduleRepository;
use App\Infrastructure\Repository\AbstractDoctrineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;

class DoctrineEventScheduleRepository extends AbstractDoctrineRepository implements EventScheduleRepository
{

    public function save(ScheduleEvent $c)
    {
        $this->em->persist($c);
    }

    /**
     * @return ArrayCollection
     * @throws \Exception
     */
    public function getAllNeedToOccur(): ArrayCollection
    {
        return new ArrayCollection($this->em
                                       ->createQuery("SELECT c FROM Event:ScheduleEvent c WHERE c.occurred = 0 and c.willOccurOn < :now")
                                       ->setParameters([
                                                           'now' => new \DateTimeImmutable(),
                                                       ])
                                       ->getResult());
    }

    /**
     * @param DomainEvent $event
     * @return Collection
     */
    public function getNotOccurredByEvent(DomainEvent $event): Collection
    {
        return new ArrayCollection(
            $this->em
                ->createQuery(
                    "
                SELECT 
                    c 
                FROM 
                    Event:ScheduleEvent c 
                WHERE 
                    c.occurred = 0 
                    and c.type = :type 
                    and c.body = :body
            ")
                ->setParameters([
                                    'type' => $event->type(),
                                    'body' => json_encode($event->body()),
                                ])->getResult()
        );
    }


}
