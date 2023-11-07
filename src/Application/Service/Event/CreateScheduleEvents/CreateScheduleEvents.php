<?php

namespace App\Application\Service\Event\CreateScheduleEvents;

use App\Domain\Entity\Event\Event;
use App\Domain\Entity\Event\ScheduleEvent;
use App\Domain\Repository\Event\EventRepository;
use App\Domain\Repository\Event\EventScheduleRepository;

class CreateScheduleEvents
{

    protected EventScheduleRepository $scheduleEventsRepository;
    protected EventRepository $eventRepository;

    public function __construct(
        EventScheduleRepository $scheduleEventsRepository,
        EventRepository $eventRepository
    )
    {
        $this->scheduleEventsRepository = $scheduleEventsRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param CreateScheduleEventsCommand $command
     */
    public function handle(CreateScheduleEventsCommand $command): void
    {
        $scheduleEvents = $this->scheduleEventsRepository->getAllNeedToOccur();

        if (!$scheduleEvents->isEmpty()) {
            /** @var ScheduleEvent $scheduleEvent */
            foreach ($scheduleEvents as $scheduleEvent) {
                $event = Event::create(
                    $scheduleEvent->body(),
                    $scheduleEvent->type(),
                    new \DateTimeImmutable()
                );

                $scheduleEvent->setOccurred();

                $this->eventRepository->save($event);
                $this->scheduleEventsRepository->save($scheduleEvent);
            }
        }

        if ($scheduleEvents->count() > 0) {
            echo date('Y-m-d H:i:s') . ': ' . $scheduleEvents->count() . ' events created!' . PHP_EOL;
        }
    }

}
