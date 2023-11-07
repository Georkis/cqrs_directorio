<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineRepository
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
