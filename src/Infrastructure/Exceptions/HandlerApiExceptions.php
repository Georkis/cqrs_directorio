<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use Exception;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class HandlerApiExceptions
 * Transform the app exceptions to the standard error messages for the API
 *
 * @package App\Infrastructure\Exceptions
 */
class HandlerApiExceptions
{
    private ApiCodeExceptions $apiCodes;

    public function __construct(
        ApiCodeExceptions $apiCodes
    )
    {
        $this->apiCodes = $apiCodes;
    }

    /**
     * @param Exception $e
     * @return array{status: int, code: string, description: string|null, errors?: array<string, non-empty-array<int, string>>}
     */
    public function handle(Exception $e): array
    {
        $errors = [];

        if (get_class($e) == InvalidCommandException::class) {
            // Exceptions in command validators
            /** @var ConstraintViolationInterface $violation */
            foreach ($e->getViolations() as $violation) {
                $errors[$this->camelCase2UnderScore($violation->getPropertyPath())][] = $violation->getMessage();
            }
            $response = $this->parseResponse(ExceptionResponse::create(
                "",
                Response::HTTP_BAD_REQUEST,
                'PARAM001',
                'Invalid parameters'
            ));
            $response['errors'] = $errors;

        } else {
            $response = $this->parseResponse($this->apiCodes->exceptionByClass(get_class($e)));
            if ($e->getMessage()) {
                $response["extra_info"] = $e->getMessage();
            }
        }


        return $response;
    }

    /**
     * @param ExceptionResponse $response
     * @return array{'status': int, 'code': string, 'description': string|null}
     */
    private function parseResponse(ExceptionResponse $response): array
    {
        return [
            'status' => $response->status(),
            'code' => $response->code(),
            'description' => $response->description(),
            //            'class'       => $response->class()
        ];
    }

    /**
     * @param string $e
     * @return array{'status': int, 'code': string, 'description': string}
     */
    public function byClassName(string $e): array
    {
        $response = $this->apiCodes->exceptionByClass($e);
        return $this->parseResponse($response);
    }

    /**
     * @param string $code
     * @return array{'status': int, 'code': string, 'description': string}
     */
    public function byCode(string $code): array
    {
        $response = $this->apiCodes->exceptionByCode($code);
        return $this->parseResponse($response);
    }

    protected function camelCase2UnderScore(string $str, string $separator = "_"): string
    {
        if (empty($str)) {
            return $str;
        }
        $str = lcfirst($str);
        $str = preg_replace("/[A-Z]/", $separator . "$0", $str);
        return strtolower($str);
    }

}
