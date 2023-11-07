<?php

declare(strict_types=1);

namespace App\Domain\Entity\Event\Role;

use App\Domain\Entity\Event\AbstractDomainEvent;
use App\Domain\Entity\Role;

abstract class AbstractRoleEvent extends AbstractDomainEvent
{
    const PREFIX = "role";

    protected array $body;

    protected function __construct(Role $role)
    {
        parent::__construct();
        $this->setBody($role);
    }

    public function body(): array
    {
        return $this->body;
    }

    protected function setBody(Role $role)
    {
        $this->body = [
            "cargo_id" => $role->id()->toString()
        ];
    }
}