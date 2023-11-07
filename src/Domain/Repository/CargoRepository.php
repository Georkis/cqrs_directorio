<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Cargo;
use Ramsey\Uuid\UuidInterface;

interface CargoRepository extends Repository
{
    public function save(Cargo $cargo): void;
    public function byId(UuidInterface $id): ?Cargo;

    public function byName(string $name): ?Cargo;
}