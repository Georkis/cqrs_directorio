<?php

namespace App\Application\Service\Event\EventProducer;

use App\Domain\Entity\Event\Event;
use App\Domain\Entity\Event\PublishedEvent;
use App\Domain\Repository\Event\EventPublishedRepository;
use App\Domain\Repository\Event\EventRepository;
use App\Infrastructure\Event\Producer\ProducerQueues;
use App\Infrastructure\Event\RabbitMqMessageProducer;
use Exception;

class EventProducer
{
    const MAX_MESSAGES = 100;
    const MAX_RETRIES_CORRECT_ID_EVENTS = 10;
    private int $retriesGetEvent = 0;
    private EventRepository $eventRepository;
    private EventPublishedRepository $eventPublishedRepository;
    private RabbitMqMessageProducer $messageProducer;
    private ProducerQueues $producerQueues;

    public function __construct(
        EventRepository $eventRepository,
        EventPublishedRepository $eventPublishedRepository,
        RabbitMqMessageProducer $messageProducer,
        ProducerQueues $producerQueues
    )
    {
        $this->eventRepository = $eventRepository;
        $this->eventPublishedRepository = $eventPublishedRepository;
        $this->messageProducer = $messageProducer;
        $this->producerQueues = $producerQueues;
    }

    /**
     * @param EventProducerCommand $command
     * @return int
     * @throws Exception
     */
    public function handle(EventProducerCommand $command)
    {
        if (!$publishedEvent = $this->eventPublishedRepository->byTypeName($command->exchangeName())) {
            $publishedEvent = PublishedEvent::create(
                $command->exchangeName(),
                0
            );
        }

        $startId = !empty($publishedEvent) ? $publishedEvent->mostRecentEventId() : 0;

        $events = $this->getEvents($startId);

        if ($events->isEmpty()) {
            return 0;
        }

        $this->messageProducer->open($command->exchangeName());

        $lastPublishedNotification = null;
        $eventTypes = $this->producerQueues->eventsByQueue($command->exchangeName());
        $messagesSent = 0;

        /** @var Event $event */
        foreach ($events as $event) {
            if (in_array($event->type(), $eventTypes)) {
                $this->publish($command->exchangeName(), $event);
                $messagesSent++;
            }
            $lastPublishedNotification = $event;
        }

        $publishedEvent->setMostRecentEventId($lastPublishedNotification->id());

        $this->eventPublishedRepository->save($publishedEvent);

        echo (new \DateTime())->format("Y-m-d H:i:s") . ': ' . $messagesSent . ' messages send!' . PHP_EOL;
    }

    private function getEvents(int $startId, \DateTimeImmutable $endDate = null)
    {
        // Recogemos los eventos aún no publicados
        $events = $this->eventRepository->allStoredEventsSince($startId, self::MAX_MESSAGES, $endDate);


        if ($events->isEmpty()) {
            return $events;
        }

        // Comprobamos que los ids de los eventos coincide con el total de eventos devueltos
        if (
            $this->retriesGetEvent < self::MAX_RETRIES_CORRECT_ID_EVENTS
            && $startId + $events->count() != $events->last()->id()
        ) {
            echo "El total de events no coincide con el ultimo id. Reintentamos recoger los eventos." . PHP_EOL;
            echo "most_recent_event_id " . $startId . PHP_EOL;
            echo "count " . $events->count() . PHP_EOL;
            echo "Last id " . $events->last()->id() . PHP_EOL;
            $this->retriesGetEvent++;
            if (empty($endDate)) {
                $endDate = $events->last()->createdAt();
            }
            sleep(1);
            return $this->getEvents($startId, $endDate);
        } else {
            $this->retriesGetEvent = 0;
            return $events;
        }
    }

    private function publish(string $exchangeName, Event $notification)
    {
        $this->messageProducer->send(
            $exchangeName,
            json_encode($notification->body()),
            $notification->type(),
            $notification->id(),
            $notification->occurredOn(),
            'fanout',
            $exchangeName
        );

        return $notification;
    }
}
