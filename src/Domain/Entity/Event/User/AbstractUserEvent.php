<?php

namespace App\Domain\Entity\Event\User;

use App\Domain\Entity\Event\AbstractDomainEvent;
use App\Domain\Entity\User;

abstract class AbstractUserEvent extends AbstractDomainEvent
{
    const PREFIX = "user";

    protected array $body;

    protected function __construct(User $user)
    {
        parent::__construct();
        $this->setBody($user);
    }

    public function body(): array
    {
        return $this->body;
    }

    protected function setBody(User $user)
    {
        $this->body = [
            "user_id" => $user->id()->toString()
        ];
    }
}
