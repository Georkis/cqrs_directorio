<?php

namespace App\Infrastructure\Event\Producer\Producers;

use App\Domain\Entity\Event\User\UserCreated;
use App\Infrastructure\Event\Producer\AbstractProducerQueues;

class MailProducer extends AbstractProducerQueues
{

    public function subscribedEvents(): array
    {
        return [
            UserCreated::TYPE
        ];
    }

    public function queueName(): string
    {
        return "mail";
    }
}
