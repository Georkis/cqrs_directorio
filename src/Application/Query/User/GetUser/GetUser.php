<?php declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use App\Application\Exception\UserNotExistExceptionException;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;

final class GetUser
{
    private UserRepository $userRepository;


    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param GetUserCommand $command
     * @return User
     * @throws UserNotExistExceptionException
     */
    public function handle(GetUserCommand $command): User
    {
        if (!$command->id() || !$user = $this->userRepository->byId(id: $command->id())) {
            throw new UserNotExistExceptionException();
        }

        return $user;

    }
}