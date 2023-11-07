<?php

namespace App\Infrastructure\Console;

use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;

abstract class AbstractConsoleCommand extends Command
{

    protected CommandBus $service;

    public function __construct(
        CommandBus $service,
    )
    {
        $this->service = $service;
        parent::__construct(null);
    }
}
