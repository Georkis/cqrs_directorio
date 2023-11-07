<?php

namespace App\Infrastructure\Exceptions\Docs;

use App\Domain\Exception\GenderNotValidException;
use App\Infrastructure\Exceptions\Docs\AbstractDocsExceptions;
use Symfony\Component\HttpFoundation\Response;

class GenderExceptionDocs extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            classname: GenderNotValidException::class,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            description: "Gender is not valid"
        );
    }

    protected function baseError(): string
    {
        return "GENDER";
    }
}