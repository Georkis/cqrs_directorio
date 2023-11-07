<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;

use App\Application\Exception\DateNotValidException;
use App\Domain\Exception\GenderNotValidException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\HttpFoundation\Response;

class GeneralExceptions extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            InvalidUuidStringException::class,
            Response::HTTP_BAD_REQUEST,
            "Invalid uuid exception"
        );

        $this->addError(
            DateNotValidException::class,
            Response::HTTP_BAD_REQUEST,
            "Date not valid"
        );

        $this->addError(
            GenderNotValidException::class,
            Response::HTTP_BAD_REQUEST,
            "Gender not valid"
        );
    }

    protected function baseError(): string
    {
        return "GENERAL";
    }
}
