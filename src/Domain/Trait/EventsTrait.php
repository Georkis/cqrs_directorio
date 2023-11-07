<?php declare(strict_types=1);

namespace App\Domain\Trait;

use App\Domain\Entity\Event\DomainEvent;
use App\Infrastructure\Event\DomainEventPublisher;

trait EventsTrait
{
    private array $events = [];

    protected ?string $createdClassEvent = null;
    protected ?string $updatedClassEvent = null;

    protected function triggerEvent(DomainEvent $event)
    {
        if (!in_array($event->type(), $this->events)) {
            DomainEventPublisher::instance()->publish($event);
            $this->events[] = $event->type();
        }
    }

    public function triggerEventCreated()
    {
        if ($this->createdClassEvent) {
            $this->triggerEvent($this->createdClassEvent::create($this));
        }
    }

    public function triggerEventUpdated()
    {
        if ($this->updatedClassEvent) {
            $this->triggerEvent($this->updatedClassEvent::create($this));
        }
    }
}
