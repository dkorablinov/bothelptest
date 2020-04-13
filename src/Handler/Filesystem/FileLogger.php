<?php

namespace Bothelp\Handler\Filesystem;

use Bothelp\Handler\Logger;

/**
 * Class FileLogger
 */
final class FileLogger implements Logger
{
    /**
     * @var
     */
    private $logFilename;

    /**
     * FileLogger constructor.
     * @param string $logFilename
     */
    public function __construct(string $logFilename)
    {
        $this->logFilename = $logFilename;
    }

    /**
     * @param string $message
     */
    public function logMessage(string $message)
    {
        $logFile = fopen($this->logFilename, 'a');
        fwrite($logFile, $message . PHP_EOL);
        fclose($logFile);
    }
}
