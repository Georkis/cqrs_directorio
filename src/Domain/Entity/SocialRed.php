<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class SocialRed extends AbstractUuidEntity
{
    protected UuidInterface $id;

    protected string $url;

    protected User $user;
    public static function create(
        UuidInterface $id,
        string $url,
        User $user
    )
    {
        $e = new static($id);
        $e->url = $url;
        $e->user = $user;

        return $e;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function setUser(User $user): ?User
    {
        return $this->user = $user;
    }
}