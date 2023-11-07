<?php declare(strict_types=1);

namespace App\Application\Query\User\LoginUser;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginUserCommand
{
    #[Assert\NotBlank]
    private string $email;
    #[Assert\NotBlank]
    private string $password;

    private bool $longLived;

    public function __construct(
        string $email,
        string $password,
        bool $longLived
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->longLived = $longLived;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function longLived(): bool
    {
        return $this->longLived;
    }

}