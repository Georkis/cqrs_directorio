<?php

namespace App\Application\Service\User\UpdateUserCargos;

use App\Application\Exception\CargoNotExistException;
use App\Domain\Entity\User;
use App\Domain\Repository\CargoRepository;
use App\Domain\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UpdateUserCargos
{
    private CargoRepository $cargoRepository;

    private UserRepository $userRepository;

    public function __construct(
        CargoRepository $cargoRepository,
        UserRepository $userRepository
    )
    {
        $this->cargoRepository = $cargoRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(UpdateUserCargosCommand $command): ?User
    {
        $cargos = new ArrayCollection();

        foreach ($command->cargos() as $cargoId){
            if(!$cargo = $this->cargoRepository->byId(Uuid::fromString($cargoId))){
                throw new CargoNotExistException();
            }

            $cargos->add($cargo);
        }

        if(!$user = $this->userRepository->byId($command->id())){
            throw new UserNotFoundException();
        }

        $user->setCargos($cargos);

        return $user;
    }
}