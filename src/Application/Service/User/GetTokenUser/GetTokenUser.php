<?php declare(strict_types=1);

namespace App\Application\Service\User\GetTokenUser;

use App\Application\Exception\UserNotExistExceptionException;
use App\Domain\Entity\Event\User\UserCreated;
use App\Domain\Entity\Token;
use App\Domain\Manager\TokenManager;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Event\DomainEventPublisher;

final class GetTokenUser
{
    const ACCESS_EXP_TIME = "+24 hours";
    const ACCESS_LONG_LIVED_EXP_TIME = "+1 month";

    private UserRepository $userRepository;
    private TokenManager $tokenManager;


    public function __construct(
        UserRepository $userRepository,
        TokenManager $tokenManager
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param GetTokenUserCommand $command
     * @return Token
     * @throws UserNotExistExceptionException
     */
    public function handle(GetTokenUserCommand $command): Token
    {
        if (!$command->id() || !$user = $this->userRepository->byId(id: $command->id())) {
            throw new UserNotExistExceptionException();
        }

        $exp = $command->longLived() ?
            (new \DateTime())->modify(self::ACCESS_LONG_LIVED_EXP_TIME)->getTimestamp()
            : (new \DateTime())->modify(self::ACCESS_EXP_TIME)->getTimestamp();

        return
            $this->tokenManager->encode(
                $user->id()->toString(),
                (new \DateTimeImmutable())->getTimestamp(),
                $exp
            );

    }
}