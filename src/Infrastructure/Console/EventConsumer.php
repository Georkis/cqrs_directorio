<?php

namespace App\Infrastructure\Console;

use App\Application\Service\Event\EventConsumer\EventConsumerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventConsumer extends AbstractConsoleCommand
{

    protected function configure()
    {
        $this
            ->setName('event:consumer')
            ->setDescription('Consumer events.')
            ->addArgument('exchange-name', InputArgument::REQUIRED, '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->service->handle(new EventConsumerCommand(
            $input->getArgument('exchange-name'),
            $input->getArgument('exchange-name')
        ));

        return 1;
    }
}
