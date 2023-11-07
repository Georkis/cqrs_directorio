<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Cargo;
use App\Domain\Repository\CargoRepository;
use Ramsey\Uuid\UuidInterface;

final class DoctrineCargoRepository extends AbstractDoctrineRepository implements CargoRepository
{
    public function save(Cargo $cargo): void
    {
        $this->em->persist($cargo);
    }
    public function byId(UuidInterface $id): ?Cargo
    {
        return $this->em->createQuery(dql: "SELECT c FROM Entity:Cargo c WHERE c.id = :id")
            ->setParameters(
                [
                    "id" => $id
                ]
            )->getOneOrNullResult();
    }

    public function byName(string $name): ?Cargo
    {
        return $this->em->createQuery(dql: "SELECT c FROM Entity:Cargo c WHERE c.name = :name")
            ->setParameters([
                "name" => $name
            ])->getOneOrNullResult();
    }
}