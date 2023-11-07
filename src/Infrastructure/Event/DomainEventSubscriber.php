<?php declare(strict_types=1);

namespace App\Infrastructure\Event;


use App\Domain\Entity\Event\DomainEvent;

interface DomainEventSubscriber
{
    /**
     * @param DomainEvent $aDomainEvent
     */
    public function handle($aDomainEvent);

    /**
     * @param DomainEvent $aDomainEvent
     * @return bool
     */
    public function isSubscribedTo($aDomainEvent);
}
