<?php declare(strict_types=1);

namespace App\Application\Service\User\RegisterUser;

use App\Application\Exception\CargoIsExistException;
use App\Application\Exception\CargoNotExistException;
use App\Application\Exception\UserEmailAlreadyRegisteredException;
use App\Domain\Entity\User;
use App\Domain\Exception\UserStatusNotValidException;
use App\Domain\Repository\CargoRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\UserStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterUser
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    private CargoRepository $cargoRepository;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        CargoRepository $cargoRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->cargoRepository = $cargoRepository;
    }

    /**
     * @param RegisterUserCommand $command
     * @throws UserEmailAlreadyRegisteredException
     * @throws UserStatusNotValidException
     */
    public function handle(RegisterUserCommand $command): void
    {
        if ($this->userRepository->byEmail(email: $command->email())) {
            throw new UserEmailAlreadyRegisteredException();
        }

        $cargos = new ArrayCollection();

        foreach ($command->cargos() as $cargoId){
            if(!$cargo = $this->cargoRepository->byId($cargoId)){
                throw new CargoNotExistException();
            }

            $cargos->add($cargo);
        }

        $user = User::create(
            id: $command->id(),
            email: $command->email(),
            name: $command->name(),
            status: UserStatus::create(status: UserStatus::ACTIVE),
            birthdate: $command->birthdate(),
            gender: $command->gender(),
            cargos: $cargos,
            avatar: $command->avatar()
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            user: $user,
            plainPassword: $command->password()
        );

        $user->setPassword(password: $hashedPassword);

        $this->userRepository->save(user: $user);
    }
}