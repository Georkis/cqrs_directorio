<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;

use App\Application\Exception\LoginDataIncorrectException;
use App\Infrastructure\Exceptions\BadCredentialsException;
use Symfony\Component\HttpFoundation\Response;

class CredentialsExceptionsDocs extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            BadCredentialsException::class,
            Response::HTTP_UNAUTHORIZED,
            "Unauthorized"
        );

        $this->addError(
            LoginDataIncorrectException::class,
            Response::HTTP_BAD_REQUEST,
            "Login data not valid"
        );
    }

    protected function baseError(): string
    {
        return "CREDENTIALS";
    }
}
