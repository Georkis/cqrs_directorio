<?php

namespace App\Domain\Entity\Event\Cargo;

use App\Domain\Entity\Cargo;

class CargoCreated extends AbstractCargoEvent
{
    const TYPE = parent::PREFIX . "." . "created";

    /**
     * @param Cargo $cargo
     * @return static
     */
    public static function create(
        Cargo $cargo
    ): self
    {
        return new self($cargo);
    }

    public function type(): string
    {
        return self::TYPE;
    }
}
