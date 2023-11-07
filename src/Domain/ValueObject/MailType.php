<?php declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\MailLogTypeNotValidException;

class MailType
{
    const USER_RESET_PASSWORD = "USER_RESET_PASSWORD";

    const VALUES = [
        self::USER_RESET_PASSWORD,
    ];

    private string $type;

    /**
     * @param int $type
     * @return MailType
     * @throws MailLogTypeNotValidException
     */
    static function create(string $type): MailType
    {
        if (!in_array($type, self::VALUES)) {
            throw new MailLogTypeNotValidException();
        }

        return new self($type);
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function equals(MailType $type): bool
    {
        return $this->type() == $type->type();
    }

    public function toString(): string
    {
        return $this->type();
    }
}