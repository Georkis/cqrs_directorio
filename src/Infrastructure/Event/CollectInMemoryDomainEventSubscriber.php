<?php declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Domain\Entity\Event\DomainEvent;

class CollectInMemoryDomainEventSubscriber implements DomainEventSubscriber
{
    protected $events = [];

    public function handle($aDomainEvent)
    {
        $this->events[] = $aDomainEvent;
    }

    /**
     * @return DomainEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }

}
