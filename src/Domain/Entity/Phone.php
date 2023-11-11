<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class Phone extends AbstractUuidEntity
{
    private string $prefix_number;
    private string $number;

    private User $user;

    public static function create(
        UuidInterface $id,
        string $prefixNumber,
        string $number
    )
    {
        $e = new static($id);
        $e->prefix_number = $prefixNumber;
        $e->number =  $number;

        return $e;
    }

    public function user(): ?User
    {
        return $this->user;
    }
}