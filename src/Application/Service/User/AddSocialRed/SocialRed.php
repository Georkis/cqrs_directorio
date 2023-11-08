<?php

namespace App\Application\Service\User\AddSocialRed;

use App\Application\Exception\SocialRedIsExistException;
use App\Domain\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use http\Exception\BadUrlException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class SocialRed
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function handle(SocialRedCommand $command)
    {
        if(!$user = $this->userRepository->byId(Uuid::fromString($command->userId()))){
            throw new UserNotFoundException();
        }

        //validamos si es una URL valida


        $listUrl = [];
        foreach ($user->getSocialReds() as $url){
            if(in_array($url->url(), $command->urls())){
                throw new SocialRedIsExistException(message: $url->url()." is used by another user!");
            }
        }



//        if(!is_array($command->urls())){
//            throw new BadUrlException();
//        }

        foreach ($command->urls() as $url){
            $user->addSocialReds(
                id: Uuid::uuid4(),
                url: $url
            );
        }

        $this->userRepository->save($user);
    }
}