<?php

namespace App\Infrastructure\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceTester extends AbstractConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('service:test')
            ->setDescription('Service tester.')
            ->setHelp('Test a Service');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       return 0;
    }
}
