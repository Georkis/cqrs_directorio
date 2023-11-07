<?php
declare(strict_types=1);

namespace App\Application\Service\Cargo;

use App\Application\Exception\CargoIsExistException;
use App\Domain\Entity\Cargo;
use App\Domain\Repository\CargoRepository;
use Ramsey\Uuid\Uuid;

final class AddCargo
{
    private CargoRepository $cargoRepository;

    public function __construct(
        CargoRepository $cargoRepository
    )
    {
        $this->cargoRepository = $cargoRepository;
    }

    public function handle(AddCargoCommand $command): void
    {
        if($this->cargoRepository->byName($command->name())){
            throw new CargoIsExistException();
        }

        $cargo = Cargo::create(
            id: Uuid::uuid4(),
            name: $command->name()
        );

        $this->cargoRepository->save($cargo);
    }
}