<?php

namespace App\Infrastructure\Repository\Event;

use App\Domain\Entity\Event\PublishedEvent;
use App\Domain\Repository\Event\EventPublishedRepository;
use App\Infrastructure\Repository\AbstractDoctrineRepository;
use Doctrine\ORM\NonUniqueResultException;

class DoctrineEventPublishedRepository extends AbstractDoctrineRepository implements EventPublishedRepository
{

    public function save(PublishedEvent $c)
    {
        $this->em->persist($c);
    }

    /**
     * @param string $type
     * @return PublishedEvent|null
     * @throws NonUniqueResultException
     */
    public function byTypeName(string $type): ?PublishedEvent
    {
        return $this->em
            ->createQuery("SELECT c FROM Event:PublishedEvent c WHERE c.type = :type")
            ->setParameters([
                                'type' => $type,
                            ])
            ->getOneOrNullResult();
    }
}
