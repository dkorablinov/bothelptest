<?php

namespace Bothelp\Handler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HandleEventsCommand
 */
final class HandleEventsCommand extends Command
{
    /**
     * @var EventHandler
     */
    private $eventHandler;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bothelp:handle-events';

    /**
     * GenerateEventsCommand constructor.
     * @param EventHandler $eventHandler
     */
    public function __construct(EventHandler $eventHandler)
    {
        $this->eventHandler = $eventHandler;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('key', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = (int) $input->getArgument('key');

        $output->writeln("Handler with a key $key started...");

        try {
            $this->eventHandler->handle($key);
        } catch (\Throwable $ex) {
            $output->writeln($ex->getMessage());
        }

        return 0;
    }
}