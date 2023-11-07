<?php

namespace App\Domain\Repository\Event;

use App\Domain\Entity\Event\PublishedEvent;

interface EventPublishedRepository
{
    public function save(PublishedEvent $c);

    /**
     * @param string $type
     * @return PublishedEvent|null
     */
    public function byTypeName(string $type): ?PublishedEvent;

}
