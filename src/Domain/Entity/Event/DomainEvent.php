<?php declare(strict_types=1);

namespace App\Domain\Entity\Event;

interface DomainEvent
{
    public function body(): array;

    public function type(): string;
}
