<?php

namespace App\Infrastructure\Console;

use App\Infrastructure\Event\Handler\HandleEventsInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventHandle extends AbstractConsoleCommand
{
    private HandleEventsInterface $handleEvents;

    public function __construct(
        CommandBus $service,
        CommandBus $query,
        HandleEventsInterface $handleEvents
    )
    {
        $this->handleEvents = $handleEvents;
        parent::__construct($service, $query);
    }

    protected function configure()
    {
        $this
            ->setName('event:handle')
            ->setDescription('Event handle.')
            ->setHelp('Handle Events')
            ->addArgument('exchange-name', InputArgument::REQUIRED, '')
            ->addArgument('type', InputArgument::REQUIRED, 'Type of the event')
            ->addArgument('timestamp', InputArgument::REQUIRED, 'Occurred on of the event')
            ->addArgument('body', InputArgument::REQUIRED, 'Body of the event');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getArgument('type');
        $body = json_decode($input->getArgument('body'), false);
        $occurredOn = $input->getArgument('timestamp');
        $exchange = $input->getArgument('exchange-name');

        return $this->handleEvents->fire($type, $body, $occurredOn, $exchange);
    }

}
