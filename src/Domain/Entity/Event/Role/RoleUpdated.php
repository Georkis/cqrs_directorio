<?php

declare(strict_types=1);

namespace App\Domain\Entity\Event\Role;

use App\Domain\Entity\Role;

class RoleUpdated extends AbstractRoleEvent
{
    const TYPE = parent::PREFIX . "." . "updated";

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