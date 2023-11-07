<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\UserStatusNotValidException;

class UserStatus
{
    const ACTIVE = 'ACTIVE';

    const INACTIVE = 'INACTIVE';

    const VALUES = [
        self::ACTIVE,
        self::INACTIVE,
    ];

    private string $status;

    /**
     * @throws UserStatusNotValidException
     */
    public static function create(string $status): UserStatus
    {
        if (! in_array($status, self::VALUES)) {
            throw new UserStatusNotValidException();
        }

        return new self($status);
    }

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function equals(UserStatus $status): bool
    {
        return $this->status() == $status->status();
    }

    public function toString(): string
    {
        return $this->status();
    }
}
