<?php

namespace App\Application\Service\Cargo;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AddCargoCommand
{
    private UuidInterface $id;
    private string $name;

    public function __construct(
        UuidInterface $id,
        string $name
    )
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}