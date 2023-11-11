<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Event\Cargo\CargoCreated;
use App\Domain\Entity\Event\Cargo\CargoUpdated;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;
use Ramsey\Uuid\UuidInterface;

class Cargo extends AbstractUuidEntity
{
    protected ?string $createdClassEvent = CargoCreated::class;
    protected ?string $updatedClassEvent = CargoUpdated::class;

    protected string $name;

    protected Collection $users;

    public static function create(
        UuidInterface $id,
        string $name
    )
    {
        $e = new static($id);
        $e->name = $name;

        return $e;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function users(): Collection
    {
        return $this->users;
    }
}