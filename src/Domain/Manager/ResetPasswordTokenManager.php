<?php declare(strict_types=1);

namespace App\Domain\Manager;

interface ResetPasswordTokenManager
{
    public function generate(string $email): string;

    public function decode(string $token): ?string;
}