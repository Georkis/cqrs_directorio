<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\GenderNotValidException;

class Gender
{
    const MALE = 'MALE';

    const FEMALE = 'FEMALE';

    const OTHER = 'OTHER';

    const VALUES = [
        self::MALE,
        self::OTHER,
    ];

    private string $gender;

    /**
     * @throws GenderNotValidException
     */
    public static function create(string $gender): Gender
    {
        if (! in_array($gender, self::VALUES)) {
            throw new GenderNotValidException();
        }

        return new self($gender);
    }

    private function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function equals(Gender $gender): bool
    {
        return $this->gender() == $gender->gender();
    }

    public function toString(): string
    {
        return $this->gender();
    }
}
