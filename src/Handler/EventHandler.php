<?php

namespace Bothelp\Handler;

/**
 * Class EventHandler
 */
interface EventHandler
{
    /**
     * @param int $key
     * @return void
     */
    public function handle(int $key);
}
