<?php declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class GetUserCommand
{
    #[Assert\NotBlank]
    private ?UuidInterface $id;

    public function __construct(
        ?UuidInterface $id
    )
    {
        $this->id = $id;
    }

    public function id(): ?UuidInterface
    {
        return $this->id;
    }


}