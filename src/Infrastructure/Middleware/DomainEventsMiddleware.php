<?php

namespace App\Infrastructure\Middleware;

use App\Domain\Entity\Event\AbstractDomainScheduleEvent;
use App\Domain\Entity\Event\Event;
use App\Domain\Entity\Event\ScheduleEvent;
use App\Domain\Repository\Event\EventRepository;
use App\Domain\Repository\Event\EventScheduleRepository;
use App\Infrastructure\Event\CollectInMemoryDomainEventSubscriber;
use App\Infrastructure\Event\DomainEventPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Tactician\Middleware;

class DomainEventsMiddleware implements Middleware
{
    protected EventRepository $eventRepository;
    private EventScheduleRepository $eventScheduleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EventRepository $eventRepository,
        EventScheduleRepository $eventScheduleRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->eventRepository = $eventRepository;
        $this->eventScheduleRepository = $eventScheduleRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws Exception
     */
    public function execute($command, callable $next)
    {
        $domainEventPublisher = DomainEventPublisher::instance();
        $domainEventsCollector = new CollectInMemoryDomainEventSubscriber();
        $subscribe_id = $domainEventPublisher->subscribe($domainEventsCollector);

        $returnValue = $next($command);

        $this->entityManager->flush();

        $events = $domainEventsCollector->getEvents();

        foreach ($events as $event) {
            $event = Event::create(
                $event->body(),
                $event->type(),
                $event->occurredOn()
            );
            $this->eventRepository->save($event);
        }
        $domainEventPublisher->unsubscribe($subscribe_id);

        return $returnValue;
    }
}
