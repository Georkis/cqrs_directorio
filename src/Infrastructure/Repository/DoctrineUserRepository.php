<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\UuidInterface;

class DoctrineUserRepository extends AbstractDoctrineRepository implements UserRepository
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->em->persist($user);
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function byEmail(string $email): ?User
    {
        return $this->em
            ->createQuery("SELECT c FROM Entity:User c WHERE c.email = :email")
            ->setParameters([
                                'email' => $email,
                            ])
            ->getOneOrNullResult();
    }

    /**
     * @param UuidInterface $id
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function byId(UuidInterface $id): ?User
    {
        return $this->em
            ->createQuery("SELECT c FROM Entity:User c WHERE c.id = :id")
            ->setParameters([
                                'id' => $id,
                            ])
            ->getOneOrNullResult();
    }
}
