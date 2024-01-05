<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class Phone extends AbstractUuidEntity
{
    private string $prefix_number;
    private string $number;

    private User $user;

    private string $name;

    public static function create(
        UuidInterface $id,
        string $prefixNumber,
        string $number,
        User $user,
        string $name
    )
    {
        $e = new static($id);
        $e->prefix_number = $prefixNumber;
        $e->number =  $number;
        $e->user = $user;
        $e->name = $name;

        return $e;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function prefix_number(): string
    {
        return $this->prefix_number;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function name(): string
    {
        return $this->name;
    }
}