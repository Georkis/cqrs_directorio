<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteExceptions extends AbstractDocsExceptions
{

    public function __construct()
    {
        $this->addError(
            NotFoundHttpException::class,
            Response::HTTP_NOT_FOUND,
            "Not found"
        );

        $this->addError(
            MethodNotAllowedHttpException::class,
            Response::HTTP_BAD_REQUEST,
            "Method not allowed"
        );
    }

    protected function baseError(): string
    {
        return "ROUTE";
    }
}
