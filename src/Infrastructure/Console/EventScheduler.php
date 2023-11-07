<?php

namespace App\Infrastructure\Console;

use App\Application\Service\Event\CreateScheduleEvents\CreateScheduleEventsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventScheduler extends AbstractConsoleCommand
{

    protected function configure()
    {
        $this
            ->setName('event:scheduler')
            ->setDescription('Event scheduler.')
            ->setHelp('Create scheduled events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i = 180; $i > 0; $i--) {
            $this->service->handle(new CreateScheduleEventsCommand());
            sleep(5);
        }

        return 1;
    }
}
