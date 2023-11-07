<?php declare(strict_types=1);

namespace App\Application\Service\User\ResetPasswordUser;

use App\Application\Exception\UserNotExistExceptionException;
use App\Application\Exception\ResetPasswordTokenNotValidException;
use App\Domain\Manager\ResetPasswordTokenManager;
use App\Domain\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetPasswordUser
{
    private UserRepository $companyRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private ResetPasswordTokenManager $resetPasswordTokenManager;

    public function __construct(
        UserRepository $companyRepository,
        UserPasswordHasherInterface $passwordHasher,
        ResetPasswordTokenManager $resetPasswordTokenManager
    )
    {
        $this->companyRepository = $companyRepository;
        $this->passwordHasher = $passwordHasher;
        $this->resetPasswordTokenManager = $resetPasswordTokenManager;
    }

    /**
     * @param ResetPasswordUserCommand $command
     * @throws UserNotExistExceptionException
     * @throws ResetPasswordTokenNotValidException
     */
    public function handle(ResetPasswordUserCommand $command): void
    {
        if (!$email = $this->resetPasswordTokenManager->decode($command->token())) {
            throw new ResetPasswordTokenNotValidException();
        }

        if (!$company = $this->companyRepository->byEmail($email)) {
            throw new UserNotExistExceptionException();
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            user: $company,
            plainPassword: $command->password()
        );

        $company->setPassword(password: $hashedPassword);

        $this->companyRepository->save($company);
    }
}