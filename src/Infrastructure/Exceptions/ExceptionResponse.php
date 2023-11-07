<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions;


class ExceptionResponse
{

    private int $status;
    private string $code;
    private ?string $description;
    private string $class;

    public static function create(
        string $class,
        int $status,
        string $code,
        ?string $description = null
    ): self {
        return new self(
            $class,
            $status,
            $code,
            $description
        );
    }

    protected function __construct(
        string $class,
        int $status,
        string $code,
        ?string $description
    ) {

        $this->class = $class;
        $this->status = $status;
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function class(): string
    {
        return $this->class;
    }

}