<?php

namespace Bothelp\Generator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateEventsCommand
 */
final class GenerateEventsCommand extends Command
{
    /**
     * @var EventGenerator
     */
    private $eventGenerator;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'bothelp:generate-events';

    /**
     * GenerateEventsCommand constructor.
     * @param EventGenerator $eventGenerator
     */
    public function __construct(EventGenerator $eventGenerator)
    {
        $this->eventGenerator = $eventGenerator;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('eventLimit', InputArgument::OPTIONAL, '', 1000);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eventLimit = (int) $input->getArgument('eventLimit');

        try {
            $this->eventGenerator->generate($eventLimit);
        } catch (\Throwable $ex) {
            $output->writeln($ex->getMessage());
        }

        return 0;
    }
}
