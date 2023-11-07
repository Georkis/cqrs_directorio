<?php

namespace App\Application;


use Symfony\Component\Process\Process;

interface EventConsumerLoggerInterface
{
    public function whenEventReceived(string $exchangeName, string $body);

    public function whenEventRequeuing(string $exchangeName, string $body, int $requeu_times);

    public function whenEventFailAndRemove(string $exchangeName, string $body);

    public function whenProcessStart(string $exchangeName, string $body, Process $process);

    public function whenProcessFail(string $exchangeName, string $body, Process $process, int $fail_times);

}
