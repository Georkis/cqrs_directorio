<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;

use App\Application\Exception\SocialRedIsExistException;
use App\Application\Exception\UserEmailAlreadyRegisteredException;
use App\Application\Exception\UserNotAccessException;
use App\Application\Exception\UserNotExistExceptionException;
use Symfony\Component\HttpFoundation\Response;

class UserExceptionsDocs extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            UserEmailAlreadyRegisteredException::class,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            "User email already registered"
        );

        $this->addError(
            UserNotExistExceptionException::class,
            Response::HTTP_BAD_REQUEST,
            "User not exists",
        );

        $this->addError(
            UserNotAccessException::class,
            Response::HTTP_FORBIDDEN,
            "User can not access"
        );

        $this->addError(
            classname: SocialRedIsExistException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Social red is exist!"
        );
    }

    protected function baseError(): string
    {
        return "USER";
    }
}
