<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

use Bothelp\Generator\GenerateEventsCommand;

$application = new Application('bothelp-test', '1.0.0');
$application->add(
    new GenerateEventsCommand(
        new \Bothelp\Generator\EventGenerator(
            new \Bothelp\EventQueue\Amqp\AmqpQueue()
        )
    )
);

$application->run();