<?php declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\Domain\Manager\TokenManager;
use App\Infrastructure\Annotations\ApiAnnotationListener;
use App\Infrastructure\Exceptions\BadCredentialsException;
use App\Infrastructure\Exceptions\HandlerApiExceptions;
use JMS\Serializer\SerializerInterface;
use League\Tactician\Middleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware implements Middleware
{

    private JsonResponse $apiResponse;
    private HandlerApiExceptions $handlerApiExceptions;
    private SerializerInterface $serializer;
    private ApiAnnotationListener $apiAnnotationListener;
    private TokenManager $tokenManager;

    public function __construct(
        HandlerApiExceptions $handlerApiExceptions,
        ApiAnnotationListener $apiAnnotationListener,
        TokenManager $tokenManager,
        SerializerInterface $serializer
    )
    {
        $this->apiResponse = new JsonResponse('', Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
        $this->handlerApiExceptions = $handlerApiExceptions;
        $this->apiAnnotationListener = $apiAnnotationListener;
        $this->tokenManager = $tokenManager;
        $this->serializer = $serializer;
    }

    public function execute($command, callable $next): JsonResponse
    {
        // Check security
        if ($this->apiAnnotationListener->secure()) {
            if (!$this->isValidToken()) {
                $result = $this->handlerApiExceptions->byClassName(BadCredentialsException::class);

                $this->apiResponse->setStatusCode($result['status']);
                $this->apiResponse->setContent($this->serializer->serialize($result, 'json'));

                return $this->apiResponse;
            }
        }

        return $next($command);
    }

    private function isValidToken(): bool
    {
        if ($token = $this->tokenManager->requestToken()) {
            if (!$token->isExpired() && !$token->isRefreshToken()) {
                return true;
            }
        }
        return false;
    }
}