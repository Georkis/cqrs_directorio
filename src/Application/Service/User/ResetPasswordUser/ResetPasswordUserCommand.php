<?php declare(strict_types=1);

namespace App\Application\Service\User\ResetPasswordUser;

use Symfony\Component\Validator\Constraints as Assert;

final class ResetPasswordUserCommand
{
    #[Assert\NotBlank]
    private string $password;
    #[Assert\NotBlank]
    private string $token;

    public function __construct(
        string $password,
        string $token
    )
    {
        $this->password = $password;
        $this->token = $token;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function token(): string
    {
        return $this->token;
    }

}