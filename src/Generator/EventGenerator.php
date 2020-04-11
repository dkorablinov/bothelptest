<?php

namespace Bothelp\Generator;

use Bothelp\EventQueue\Event;
use Bothelp\EventQueue\QueueInterface;

/**
 * Class EventGenerator
 */
final class EventGenerator
{
    private const CLIENT_COUNT = 1000;

    /**
     * @var QueueInterface
     */
    private $eventQueue;

    /**
     * @var array
     */
    private $clientCounters = [];

    /**
     * EventGenerator constructor.
     * @param QueueInterface $eventQueue
     */
    public function __construct(QueueInterface $eventQueue)
    {
        $this->eventQueue = $eventQueue;
    }

    /**
     * @param int $limit
     * @throws \Exception
     */
    public function generate(int $limit)
    {
        $eventsGenerated = 0;
        while ($eventsGenerated < $limit) {
            $clientId = rand(1, self::CLIENT_COUNT);
            if (!isset($this->clientCounters[$clientId])) {
                $this->clientCounters[$clientId] = 0;
            }

            $eventsGeneratedForClient = $this->generateEventsForClient($clientId);
            $this->clientCounters[$clientId] += $eventsGeneratedForClient;
            $eventsGenerated += $eventsGeneratedForClient;
        }
    }

    /**
     * @param int $clientId
     * @return int
     * @throws \Exception
     */
    private function generateEventsForClient(int $clientId): int
    {
        $count = rand(1, 5);
        for ($i = 0; $i < $count; $i++) {
            $this->eventQueue->pushEvent(
                new Event($clientId, $this->clientCounters[$clientId] + $i)
            );
        }

        return $count;
    }
}
