<?php declare(strict_types=1);

namespace App\Infrastructure\Manager;

use App\Domain\Entity\Token;
use App\Domain\Manager\TokenManager;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTTokenManager implements TokenManager
{
    const AUTH_HEADER = 'Authorization';

    protected string $key;
    protected string $algorithm;
    protected ?Token $token = null;
    private string $iss;

    public function __construct(string $algorithm, string $key, string $iss, ?RequestStack $requestStack)
    {
        $this->algorithm = $algorithm;
        $this->key = $key;
        $this->iss = $iss;

        if (
            !empty($requestStack)
            && !empty($requestStack->getCurrentRequest())
            && $headerAuth = $requestStack->getCurrentRequest()->headers->get(self::AUTH_HEADER)
        ) {
            $bearerPos = strpos(haystack: $headerAuth, needle: "Bearer ");
            if (is_numeric($bearerPos) && $bearerPos == 0) {
                $this->token = $this->decode(substr(string: $headerAuth, offset: 7));
            }

        }
    }

    public function encode(string $aud, int $iat, int $exp): Token
    {
        return new Token(
            JWT::encode([
                            'iss' => $this->iss,
                            'aud' => $aud,
                            'iat' => $iat,
                            'exp' => $exp
                        ],
                        $this->key,
                        $this->algorithm
            ),
            $this->iss,
            $aud,
            $iat,
            $exp
        );
    }

    public function decode(string $token): ?Token
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->algorithm));

            return new Token(
                $token,
                $decoded->iss,
                $decoded->aud,
                $decoded->iat,
                $decoded->exp
            );
        } catch (Exception $e) {
        }

        return null;
    }


    public function requestToken(): ?Token
    {
        return $this->token;
    }
}