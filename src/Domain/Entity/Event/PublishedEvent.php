<?php

namespace App\Domain\Entity\Event;

use App\Domain\Entity\AbstractUuidEntity;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PublishedEvent extends AbstractUuidEntity
{

    private string $type;

    private int $mostRecentEventId;

    protected function __construct(
        UuidInterface $id,
        string $type,
        int $mostRecentEventId
    )
    {
        $this->type = $type;
        $this->mostRecentEventId = $mostRecentEventId;
        parent::__construct($id);
    }

    /**
     * @param string $type
     * @param int $mostRecentEventId
     * @return PublishedEvent
     * @throws Exception
     */
    public static function create(
        string $type,
        int $mostRecentEventId
    ): PublishedEvent
    {
        return new self(
            Uuid::uuid4(),
            $type,
            $mostRecentEventId
        );
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function mostRecentEventId(): int
    {
        return $this->mostRecentEventId;
    }

    public function setMostRecentEventId(int $lastPublishedNotification)
    {
        $this->mostRecentEventId = $lastPublishedNotification;
    }
}
