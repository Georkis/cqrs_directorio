<?php

namespace App\Infrastructure\Event;

use App\Application\EventConsumerLoggerInterface;
use Symfony\Component\Process\Process;

class BasicEventConsumerLogger implements EventConsumerLoggerInterface
{

    public function whenEventReceived(string $exchangeName, string $body)
    {
        // TODO: Implement whenEventReceived() method.
    }

    public function whenEventRequeuing(string $exchangeName, string $body, int $requeu_times)
    {
        // TODO: Implement whenEventRequeuing() method.
    }

    public function whenEventFailAndRemove(string $exchangeName, string $body)
    {
        // TODO: Implement whenEventFailAndRemove() method.
    }

    public function whenProcessStart(string $exchangeName, string $body, Process $process)
    {
        // TODO: Implement whenProcessStart() method.
    }

    public function whenProcessFail(string $exchangeName, string $body, Process $process, int $fail_times)
    {
        // TODO: Implement whenProcessFail() method.
    }
}
