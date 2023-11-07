<?php declare(strict_types=1);

namespace App\Application\Service\Mail\User\SendResetPasswordUserMail;

use App\Application\Exception\ErrorSendMailSendgridException;
use App\Application\Service\Mail\AbstractSendMailService;
use App\Domain\Exception\MailLogTypeNotValidException;
use App\Domain\Manager\ResetPasswordTokenManager;
use App\Domain\Repository\MailLogRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\ValueObject\MailType;
use SendGrid\Mail\TypeException;

final class SendResetPasswordUserMail extends AbstractSendMailService
{
    private UserRepository $userRepository;
    private string $template;
    private string $resetPasswordUrl;
    private ResetPasswordTokenManager $resetPasswordTokenManager;

    public function __construct(
        MailLogRepository $mailLogRepository,
        string $sendgridApiKey,
        string $sender,
        string $senderName,
        string $template,
        string $env,
        array $devDomains,
        string $resetPasswordUrl,
        UserRepository $userRepository,
        ResetPasswordTokenManager $resetPasswordTokenManager
    )
    {
        parent::__construct($mailLogRepository, $sendgridApiKey, $sender, $senderName, $env, $devDomains);
        $this->userRepository = $userRepository;
        $this->template = $template;
        $this->resetPasswordUrl = $resetPasswordUrl;
        $this->resetPasswordTokenManager = $resetPasswordTokenManager;
    }

    /**
     * @param SendResetPasswordUserMailCommand $command
     * @throws ErrorSendMailSendgridException
     * @throws MailLogTypeNotValidException
     * @throws TypeException
     */
    public function handle(SendResetPasswordUserMailCommand $command): void
    {
        if (!$user = $this->userRepository->byEmail(email: $command->email())) {
            return;
        }

        $token = $this->resetPasswordTokenManager->generate($user->email());

        $urlResetPassword = $this->resetPasswordUrl . "?token=" . urlencode($token);

        $this->sendMail(
            type: MailType::create(MailType::USER_RESET_PASSWORD),
            template: $this->template,
            recipient: $user->email(),
            name: $user->name(),
            substitutions: [
                      "user_name" => $user->name(),
                      "url_reset_password" => $urlResetPassword
                  ]
        );
    }
}