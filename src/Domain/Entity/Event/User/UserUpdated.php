<?php

namespace App\Domain\Entity\Event\User;

use App\Domain\Entity\User;

class UserUpdated extends AbstractUserEvent
{
    const TYPE = parent::PREFIX . "." . "updated";

    /**
     * @param User $user
     * @return static
     */
    public static function create(
        User $user
    ): self
    {
        return new self($user);
    }

    public function type(): string
    {
        return self::TYPE;
    }
}
