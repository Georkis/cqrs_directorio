<?php declare(strict_types=1);

namespace App\Application\Service\Mail\User\SendResetPasswordUserMail;

use Symfony\Component\Validator\Constraints as Assert;

final class SendResetPasswordUserMailCommand
{
    #[Assert\NotBlank]
    private string $email;

    public function __construct(
        string $email
    )
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }

}