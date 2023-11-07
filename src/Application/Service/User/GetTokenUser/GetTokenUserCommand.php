<?php declare(strict_types=1);

namespace App\Application\Service\User\GetTokenUser;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class GetTokenUserCommand
{
    #[Assert\NotBlank]
    private ?UuidInterface $id;

    private bool $longLived;

    public function __construct(
        ?UuidInterface $id,
        bool $longLived
    )
    {
        $this->id = $id;
        $this->longLived = $longLived;
    }

    public function id(): ?UuidInterface
    {
        return $this->id;
    }

    public function longLived(): bool
    {
        return $this->longLived;
    }


}