<?php

namespace Bothelp\Handler;

/**
 * Interface Logger
 */
interface Logger
{
    /**
     * @param string $message
     * @return void
     */
    public function logMessage(string $message);
}
