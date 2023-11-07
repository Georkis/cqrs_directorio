<?php declare(strict_types=1);

namespace App\Infrastructure\Manager;

use App\Domain\Entity\Token;
use App\Domain\Manager\ResetPasswordTokenManager;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTResetPasswordTokenManager implements ResetPasswordTokenManager
{
    const EXPIRATION_TIME = "+30 minutes";

    protected string $key;
    protected string $algorithm;
    protected ?Token $token = null;

    public function __construct(
        string $algorithm,
        string $key
    )
    {
        $this->algorithm = $algorithm;
        $this->key = $key;
    }

    public function generate(string $email): string
    {
        return base64_encode(JWT::encode([
                               'email' => $email,
                               'exp' => (new \DateTimeImmutable())->modify(self::EXPIRATION_TIME)->getTimestamp()
                           ],
                           $this->key,
                           $this->algorithm
        ));
    }

    public function decode(string $token): ?string
    {
        try {
            $decoded = JWT::decode(base64_decode($token), new Key($this->key, $this->algorithm));

            if (!$decoded->exp || $decoded->exp < (new \DateTimeImmutable())->getTimestamp()) {
                return null;
            }

            return $decoded->email;
        } catch (Exception $e) {
        }

        return null;
    }
}