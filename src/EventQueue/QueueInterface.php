<?php

namespace Bothelp\EventQueue;

/**
 * Interface QueueInterface
 */
interface QueueInterface
{
    /**
     * @param Event $event
     */
    public function pushEvent(Event $event): void;
}
