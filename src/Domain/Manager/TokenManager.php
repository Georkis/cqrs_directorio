<?php declare(strict_types=1);

namespace App\Domain\Manager;

use App\Domain\Entity\Token;

interface TokenManager
{
    public function encode(
        string $aud,
        int $iat,
        int $exp
    ): Token;

    public function decode(string $token): ?Token;

    public function requestToken(): ?Token;
}