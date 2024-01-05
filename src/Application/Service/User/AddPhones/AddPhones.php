<?php

namespace App\Application\Service\User\AddPhones;

use App\Application\Exception\PhoneIsExistException;
use App\Domain\Entity\User;
use App\Domain\Repository\PhoneRepository;
use App\Domain\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class AddPhones
{
    public function __construct(
        private UserRepository $userRepository,
        private PhoneRepository $phoneRepository
    )
    {

    }

    public function handle(AddPhonesCommand $command): ?User
    {
        if(!$user = $this->userRepository->byId($command->id())){
            throw new UserNotFoundException();
        }

        foreach ($command->phones() as $phone){
            if($this->phoneRepository->byNumber(number: $phone['number'])){
                throw new PhoneIsExistException();
            }
        }

        $user->setPhones(
            $command->phones()
        );

        return $user;
    }
}