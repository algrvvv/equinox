<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\core\Facades\src;

use Imissher\Equinox\app\core\Application;

class Log
{
    /**
     * @var string путь к файлу с логами
     */
    private string $pathToLogFile;

    /**
     * @param string $pathToLogFile
     */
    public function __construct(string $pathToLogFile = '')
    {
        $this->pathToLogFile = Application::$ROOT_PATH . "/app/storage/logs/" .  $pathToLogFile . ".log";
    }

    /**
     * Обертка для создания лога
     *
     * @param string $message
     * @return void
     */
    public function info(string $message): void
    {
        $this->log("INFO - " . $message);
    }

    /**
     * Обертка для создания лога ошибки
     *
     * @param string $message
     * @return void
     */
    public function error(string $message): void
    {
        $this->log("ERROR - " . $message);
    }

    /**
     * Обертка для создания лога дебага
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message): void
    {
        $this->log("DEBUG - " . $message);
    }

    /**
     * Создание лога
     *
     * @param string $message
     * @return void
     */
    private function log(string $message): void
    {
        $content = file_get_contents($this->pathToLogFile);
        $log = "[" . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
        if ($content == "")
            file_put_contents($this->pathToLogFile, $log);
        else
            file_put_contents($this->pathToLogFile, $content . "\n" . $log);
    }

}
