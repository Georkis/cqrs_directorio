<?php declare(strict_types=1);

namespace App\Domain\Entity\Event;

use App\Domain\Entity\AbstractEntity;
use DateTimeImmutable;

class Event extends AbstractEntity implements DomainEvent
{
    private int $id;

    private string $body;

    private string $type;

    private DateTimeImmutable $occurredOn;

    protected function __construct(
        array $body,
        string $type,
        DateTimeImmutable $occurredOn
    )
    {
        parent::__construct();
        $this->body = json_encode($body);
        $this->type = $type;
        $this->occurredOn = $occurredOn;
    }

    /**
     * @param array $body
     * @param string $type
     * @param DateTimeImmutable $occurredOn
     * @return Event
     */
    public static function create(
        array $body,
        string $type,
        DateTimeImmutable $occurredOn
    ): Event
    {
        return new self(
            $body,
            $type,
            $occurredOn
        );
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function body(): array
    {
        return json_decode($this->body, true);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
