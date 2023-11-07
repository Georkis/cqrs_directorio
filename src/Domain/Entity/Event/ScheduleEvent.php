<?php

namespace App\Domain\Entity\Event;

use App\Domain\Entity\AbstractUuidEntity;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ScheduleEvent extends AbstractUuidEntity
{

    private bool $occurred;

    private DateTimeImmutable $willOccurOn;

    private string $type;

    private string $body;

    protected function __construct(
        UuidInterface $id,
        array $body,
        string $type,
        DateTimeImmutable $willOccurOn
    )
    {
        $this->type = $type;
        $this->body = json_encode($body);
        $this->willOccurOn = $willOccurOn;
        $this->occurred = false;
        parent::__construct($id);
    }

    /**
     * @param DomainEvent $event
     * @param DateTimeImmutable $willOccurOn
     * @return ScheduleEvent
     */
    public static function create(
        DomainEvent $event,
        DateTimeImmutable $willOccurOn
    ): ScheduleEvent
    {
        return new self(
            Uuid::uuid4(),
            $event->body(),
            $event->type(),
            $willOccurOn
        );
    }

    /**
     * @return bool
     */
    public function occurred(): bool
    {
        return $this->occurred;
    }

    /**
     * @return DateTimeImmutable
     */
    public function willOccurOn(): DateTimeImmutable
    {
        return $this->willOccurOn;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function body(): array
    {
        return json_decode($this->body, true);
    }

    public function setOccurred()
    {
        $this->occurred = true;
    }

    public static function toNearestMinuteInterval(\DateTime $dateTime, $minuteInterval = 10): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($dateTime->setTime(
            $dateTime->format('H'),
            (ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval) + $minuteInterval,
            0
        ));
    }
}