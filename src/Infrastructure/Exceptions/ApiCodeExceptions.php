<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Infrastructure\Exceptions\Docs\AbstractDocsExceptions;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiCodeExceptions
{
    /**
     * @var ExceptionResponse[]
     */
    private array $codes = [];

    /**
     * ApiCodeExceptions constructor.
     * @param AbstractDocsExceptions[] $exceptions
     */
    public function __construct(iterable $exceptions)
    {
//        dd($exceptions);
        foreach ($exceptions as $exception) {
            foreach ($exception->errors() as $error) {
                $this->addException(ExceptionResponse::create(
                    $error['class'],
                    $error['status'],
                    $error['code'],
                    $error['description']
                ));
            }
        }
    }

    public function addException(ExceptionResponse $exceptionResponse): void
    {
        $this->codes[] = $exceptionResponse;
    }

    public function defaultException(): ExceptionResponse
    {
        return ExceptionResponse::create(
            Exception::class,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'ERROR',
            'General error'
        );

    }

    public function exceptionByCode(string $code): ExceptionResponse
    {
        $response = array_filter($this->codes, function (ExceptionResponse $el) use ($code) {
            return $el->code() == $code;
        });

        return !empty($response) ? array_shift($response) : $this->defaultException();

    }

    public function exceptionByClass(string $class): ExceptionResponse
    {
//        dd($class);
        $response = array_filter($this->codes, function (ExceptionResponse $el) use ($class) {
            return $el->class() == $class;
        });

        if(!empty($response)){
            return array_shift($response);
        }

        $response = $this->defaultException();
//        $response->setTitle($class);

        return $response;
    }
}