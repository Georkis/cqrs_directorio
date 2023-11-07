<?php

namespace App\Application\Service\SMS;

class SmsCommand
{
    private string $toPhone;
    private string $message;

    public function __construct(
        string $toPhone,
        string $message
    )
    {
        $this->toPhone = $toPhone;
        $this->message = $message;
    }

    public function toPhone():string
    {
        return $this->toPhone;
    }

    public function message(): string
    {
        return $this->message;
    }
}