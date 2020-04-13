<?php

namespace Bothelp\Generator;

use Bothelp\Event\Event;

/**
 * Class EventGenerator
 */
final class EventGenerator
{
    /**
     * @var EventPublisher
     */
    private $eventPublisher;

    /**
     * @var int
     */
    private $clientAmount;

    /**
     * @var array
     */
    private $clientCounters = [];

    /**
     * EventGenerator constructor.
     * @param EventPublisher $eventPublisher
     * @param int $clientAmount
     */
    public function __construct(EventPublisher $eventPublisher, int $clientAmount)
    {
        $this->eventPublisher = $eventPublisher;
        $this->clientAmount = $clientAmount;
    }

    /**
     * @param int $limit
     * @throws \Exception
     */
    public function generate(int $limit)
    {
        $eventsGenerated = 0;
        while ($eventsGenerated < $limit) {
            // Choose random client
            $clientId = rand(1, $this->clientAmount);
            if (!isset($this->clientCounters[$clientId])) {
                $this->clientCounters[$clientId] = 0;
            }

            // Generate events for client
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
            // Publish an event to the queue
            $this->eventPublisher->publishEvent(
                new Event($clientId, $this->clientCounters[$clientId] + $i)
            );
        }

        return $count;
    }
}
