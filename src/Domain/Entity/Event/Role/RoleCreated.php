<?php

namespace App\Domain\Entity\Event\Role;

use App\Domain\Entity\Role;

class RoleCreated extends AbstractRoleEvent
{
    const TYPE = parent::PREFIX . "." . "created";

    /**
     * @param Role $role
     * @return static
     */
    public static function create(
        Role $role
    ): self
    {
        return new self($role);
    }

    public function type(): string
    {
        return self::TYPE;
    }
}