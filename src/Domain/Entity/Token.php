<?php declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;

class Token
{
    private string $token;
    private string $iss;
    private string $aud;
    private int $iat;
    private int $exp;

    public function __construct(
        string $token,
        string $iss,
        string $aud,
        int $iat,
        int $exp
    )
    {
        $this->token = $token;
        $this->aud = $aud;
        $this->iss = $iss;
        $this->iat = $iat;
        $this->exp = $exp;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function aud(): string
    {
        return $this->aud;
    }

    public function iss(): string
    {
        return $this->iss;
    }

    public function iat(): int
    {
        return $this->iat;
    }

    public function exp(): int
    {
        return $this->exp;
    }

    public function isExpired(): bool
    {
        return !$this->exp() || $this->exp() < (new DateTimeImmutable())->getTimestamp();
    }

    public function isRefreshToken(): bool
    {
        return !empty($this->refreshToken);
    }
}