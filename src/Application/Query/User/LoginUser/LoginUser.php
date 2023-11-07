<?php declare(strict_types=1);

namespace App\Application\Query\User\LoginUser;

use App\Application\Exception\LoginDataIncorrectException;
use App\Domain\Entity\Token;
use App\Domain\Manager\TokenManager;
use App\Domain\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginUser
{
    const ACCESS_EXP_TIME = "+24 hours";
    const ACCESS_LONG_LIVED_EXP_TIME = "+1 month";

    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private TokenManager $tokenManager;


    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        TokenManager $tokenManager
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param LoginUserCommand $command
     * @return Token
     * @throws LoginDataIncorrectException
     */
    public function handle(LoginUserCommand $command): Token
    {
        if (!$user = $this->userRepository->byEmail(email: $command->email())) {
            throw new LoginDataIncorrectException();
        }

        if (!$this->passwordHasher->isPasswordValid(user: $user, plainPassword: $command->password())) {
            throw new LoginDataIncorrectException();
        }

        $exp = $command->longLived() ?
            (new \DateTime())->modify(self::ACCESS_LONG_LIVED_EXP_TIME)->getTimestamp()
            : (new \DateTime())->modify(self::ACCESS_EXP_TIME)->getTimestamp();

        return
            $this->tokenManager->encode(
                $user->id()->toString(),
                (new \DateTimeImmutable())->getTimestamp(),
                $exp
            )
        ;
    }
}