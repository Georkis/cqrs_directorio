<?php

namespace App\Infrastructure\Event\Handler;

use League\Tactician\CommandBus;

abstract class AbstractEventHandle implements EventHandlerInterface
{

    protected CommandBus $commandBus;
    protected string $appEnv;

    public function __construct(
        CommandBus $commandBus,
        string $appEnv
    )
    {
        $this->commandBus = $commandBus;
        $this->appEnv = $appEnv;
    }

}
