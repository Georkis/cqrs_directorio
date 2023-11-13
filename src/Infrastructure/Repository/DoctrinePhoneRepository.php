<?php
declare(strict_types = 1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Phone;
use App\Domain\Repository\PhoneRepository;

final class DoctrinePhoneRepository extends AbstractDoctrineRepository implements PhoneRepository
{
    public function byNumber(string $number): ?Phone
    {
        return $this->em->createQuery("SELECT c FROM Entity:Phone c WHERE c.number = :number")
            ->setParameter(key: "number", value: $number)
            ->getOneOrNullResult();
    }
}