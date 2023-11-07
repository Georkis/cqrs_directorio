<?php

namespace App\Infrastructure\Console;

use App\Application\Service\Event\EventProducer\EventProducerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventProducer extends AbstractConsoleCommand
{
    protected function configure()
    {
        $this
            ->setName('event:producer')
            ->setDescription('Event producer.')
            ->setHelp('Produced Events')
            ->addArgument('exchange-name', InputArgument::REQUIRED, '');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 300; $i > 0; $i--) {
            $this->service->handle(new EventProducerCommand(
                $input->getArgument('exchange-name')
            ));
            //echo (new \DateTime())->format("Y-m-d H:i:s") . ': ' . $totalMessages . ' messages send!' . "\n";
            sleep(3);
        }
        return 1;
    }
}
