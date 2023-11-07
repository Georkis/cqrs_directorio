<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\API\App;

use App\Application\Service\Mail\User\SendResetPasswordUserMail\SendResetPasswordUserMailCommand;
use App\Application\Service\User\GetTokenUser\GetTokenUserCommand;
use App\Application\Query\User\GetUser\GetUserCommand;
use App\Application\Query\User\LoginUser\LoginUserCommand;
use App\Application\Service\User\RegisterUser\RegisterUserCommand;
use App\Application\Service\User\ResetPasswordUser\ResetPasswordUserCommand;
use App\Application\Service\User\UpdateUserCargos\UpdateUserCargosCommand;
use App\Infrastructure\Annotations\ApiAnnotation;
use App\Infrastructure\Controller\API\AbstractAPIController;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractAPIController
{
    #[Route('/api/app/user/register', name: 'app_user_register', methods: ['POST'])]
    public function create(): Response
    {
        return $this->command->handle(
            new RegisterUserCommand(
                id: Uuid::uuid4(),
                email: (string)$this->params->get("email"),
                name: (string)$this->params->get("name"),
                password: (string)$this->params->get("password"),
                birthdate: (string)$this->params->get("birthdate"),
                gender: (string)$this->params->get("gender"),
                cargos: (array)$this->params->get("cargos")
            )
        );
    }

    #[Route('/api/app/user/login', name: 'app_user_login', methods: ['POST'])]
    public function login(): Response
    {
        return $this->query->handle(
            new LoginUserCommand(
                email: (string)$this->params->get("email"),
                password: (string)$this->params->get("password"),
                longLived: !empty($this->params->get("long_lived")),
            )
        );
    }

    #[Route('/api/app/user/data', name: 'app_user_data', methods: ['GET']), ApiAnnotation(secure: true)]
    public function data(): Response
    {
        return $this->query->handle(
            new GetUserCommand(
                id: $this->tokenManager->requestToken() ? Uuid::fromString($this->tokenManager->requestToken()->aud()) : null,
            )
        );
    }

    #[Route('/api/app/user/token', name: 'app_user_token', methods: ['POST']), ApiAnnotation(secure: true)]
    public function token(): Response
    {
        return $this->query->handle(
            new GetTokenUserCommand(
                id: $this->tokenManager->requestToken() ? Uuid::fromString($this->tokenManager->requestToken()->aud()) : null,
                longLived: !empty($this->params->get("long_lived")),
            )
        );
    }

    #[Route('/api/app/user/send-reset-password-mail', name: 'user_send_reset_password_mail', methods: ['POST'])]
    public function sendResetPasswordMail(): Response
    {
        return $this->command->handle(
            new SendResetPasswordUserMailCommand(
                email: (string)$this->params->get("email"),
            )
        );
    }

    #[Route('/api/app/user/reset-password', name: 'user_reset_password', methods: ['POST'])]
    public function resetPassword(): Response
    {
        return $this->command->handle(
            new ResetPasswordUserCommand(
                password: (string)$this->params->get("password"),
                token: (string)$this->params->get("token"),
            )
        );
    }

    #[Route(path: '/api/app/user/set/cargos', name: "user_cargos", methods: ["POST"]), ApiAnnotation(secure: true)]
    public function updateUserCargos():Response
    {
        return $this->command->handle(
            new UpdateUserCargosCommand(
                id: $this->tokenManager->requestToken() ? Uuid::fromString($this->tokenManager->requestToken()->aud()) : "",
                cargos: is_array($this->params->get("cargos")) ? $this->params->get("cargos") : []
            )
        );
    }

    #[Route(path: '/api/app/user/info', name: 'api_app_user_info'), ApiAnnotation(secure: true)]
    public function infoUser(): void
    {

    }
}
