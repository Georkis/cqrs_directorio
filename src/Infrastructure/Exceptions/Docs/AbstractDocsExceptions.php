<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions\Docs;


abstract class AbstractDocsExceptions
{
    /**
     * @var array{'code': string, "class": string, 'status': int, 'description': string}[]
     */
    private array $errors;

    protected int $position = 0;

    protected function addError(
        string $classname,
        int $status,
        string $description = null
    ): void {
        $this->position++;

        $this->errors[] = [
            'code'        => $this->baseError() . sprintf('%03d', $this->position),
            'class'       => $classname,
            'status'      => $status,
            'description' => $description,
        ];
    }

    /**
     * @return array{'code': string, "class": string, 'status': int, 'description': string}[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    abstract protected function baseError(): string;

}