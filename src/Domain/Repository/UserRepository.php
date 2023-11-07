<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepository extends Repository
{
    public function save(User $user): void;

    public function byEmail(string $email): ?User;

    public function byId(UuidInterface $id): ?User;
}