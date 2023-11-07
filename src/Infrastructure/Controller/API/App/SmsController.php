<?php

namespace App\Infrastructure\Controller\API\App;

use App\Application\Service\SMS\SmsCommand;
use App\Infrastructure\Controller\API\AbstractAPIController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractAPIController
{
    #[Route(path: '/api/app/sms', name: 'app_sms', methods: ['POST'])]
    public function sms(): Response
    {
        return $this->command->handle(
            new SmsCommand(
                toPhone: $this->params->get('toPhone'),
                message: $this->params->get('message')
            )
        );
    }
}