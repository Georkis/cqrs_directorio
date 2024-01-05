<?php

namespace App\Application\Service\User\AddPhones;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddPhonesCommand
{
    #[Assert\NotBlank]
    private UuidInterface $id;

    #[Assert\NotBlank]
    private array $phones;

    /**
     * @param UuidInterface $id
     * @param array $phones
     */
    public function __construct(
        UuidInterface $id,
        array $phones,
    )
    {
        $this->id = $id;
        $this->phones = $phones;
    }

    public function id(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function phones(): array
    {
        return $this->phones;
    }
}