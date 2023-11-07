<?php

namespace App\Domain\Entity\Event\Cargo;


use App\Domain\Entity\Cargo;

class CargoUpdated extends AbstractCargoEvent
{
    const TYPE = parent::PREFIX . "." . "updated";

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
