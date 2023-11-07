<?php

namespace App\Application\Service\SMS;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Twilio\Rest\Client;

class Sms
{

    private Client $twilioClient;
    public function __construct(
        Client $twilioClient
    )
    {
        $this->twilioClient = $twilioClient;
    }

    public function handle(SmsCommand $command): JsonResponse
    {
        $message = $this->twilioClient->messages->create(
            to: $command->toPhone(),
            options: [
                'from' => '+16813846480',
                'body' => $command->message()
            ]
        );

        return new JsonResponse([
            "message" => "SMS sent with message ID: ".$message->sid
        ]);
    }
}