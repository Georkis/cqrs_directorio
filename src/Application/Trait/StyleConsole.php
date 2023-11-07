<?php

namespace App\Application\Trait;

use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

trait StyleConsole
{
    function __construct(
        InputInterface $input,
        OutputInterface $output
    )
    {
        $this->input = $input;
        $this->output = $output;
    }

    private function symfonyStyle(): SymfonyStyle
    {
        return new SymfonyStyle(InputInterface::class, OutputInterface::class);
    }
    protected function success(string $message): void
    {
        $this->symfonyStyle()->success($message);
    }

    protected function failure(string $message):void
    {
        $this->symfonyStyle()->error($message);
    }
}