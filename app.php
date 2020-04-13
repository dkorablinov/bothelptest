<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

use Bothelp\Generator\GenerateEventsCommand;
use Bothelp\Handler\HandleEventsCommand;

const HANDLER_AMOUNT = 5;

$config = require_once __DIR__ . '/config/config.php';

$logger = new \Bothelp\Handler\Filesystem\FileLogger(__DIR__.'/logs/result.log');

$eventPublisher = new \Bothelp\Generator\Amqp\EventPublisher($config['amqp'], $config['consumerAmount']);
$eventHandler = new \Bothelp\Handler\Amqp\EventHandler($config['amqp'], $logger);

$application = new Application('bothelp-test', '1.0.0');
$application->add(
    new GenerateEventsCommand(
        new \Bothelp\Generator\EventGenerator($eventPublisher, $config['clientAmount'])
    )
);
$application->add(
    new HandleEventsCommand($eventHandler)
);

$application->run();