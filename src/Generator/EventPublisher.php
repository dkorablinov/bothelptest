<?php

namespace Bothelp\Generator;

use Bothelp\Event\Event;

/**
 * Class EventPublisher
 */
interface EventPublisher
{
    /**
     * @param Event $event
     */
    public function publishEvent(Event $event): void;
}
