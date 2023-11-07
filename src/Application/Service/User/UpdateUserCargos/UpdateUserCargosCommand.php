<?php

namespace App\Application\Service\User\UpdateUserCargos;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserCargosCommand
{
    #[Assert\NotBlank]
    private UuidInterface $id;

    #[Assert\NotBlank]
    private array $cargos;

    public function __construct(
        UuidInterface $id,
        array $cargos
    )
    {
        $this->id = $id;
        $this->cargos = $cargos;
    }

    public function id(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function cargos(): array
    {
        $r = [];
        foreach ($this->cargos as $cargo){
            $r[] = $cargo;
        }

        return $r;
    }
}