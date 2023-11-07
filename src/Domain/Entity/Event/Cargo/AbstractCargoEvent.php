<?php

namespace App\Domain\Entity\Event\Cargo;

use App\Domain\Entity\Event\AbstractDomainEvent;
use App\Domain\Entity\Cargo;

abstract class AbstractCargoEvent extends AbstractDomainEvent
{
    const PREFIX = "cargo";

    protected array $body;

    protected function __construct(Cargo $cargo)
    {
        parent::__construct();
        $this->setBody($cargo);
    }

    public function body(): array
    {
        return $this->body;
    }

    protected function setBody(Cargo $cargo)
    {
        $this->body = [
            "cargo_id" => $cargo->id()->toString()
        ];
    }
}
