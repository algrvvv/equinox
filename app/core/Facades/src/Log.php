<?php
/*
 * Copyright (c) 2024 p4xt3r. All rights reserved.
 */

namespace Imissher\Equinox\app\core\Facades\src;

use Exception;
use Imissher\Equinox\app\core\Application;
use Imissher\Equinox\app\core\exceptions\FailedToOpenStream;

class Log
{
    /**
     * @var string путь к файлу с логами
     */
    private string $pathToLogFile;

    private array $caller = [
        'file' => '',
        'line' => ''
    ];

    /**
     * @param string $pathToLogFile
     * @throws FailedToOpenStream
     */
    public function __construct(string $pathToLogFile = '')
    {
        if ($pathToLogFile == '')
            $this->pathToLogFile = Application::$ROOT_PATH . "/app/storage/logs/" . config('app', 'log.filename') . ".log";
        else
            $this->pathToLogFile = Application::$ROOT_PATH . "/app/storage/logs/" . $pathToLogFile . ".log";
    }

    /**
     * Обертка для создания лога
     *
     * @param string $message
     * @param bool $showFile
     * @return void
     */
    public function info(string $message, bool $showFile = false): void
    {
        $this->caller['file'] = debug_backtrace()[1]['file'];
        $this->caller['line'] = debug_backtrace()[1]['line'];
        $this->log("INFO - " . $message, $showFile);
    }

    /**
     * Обертка для создания лога ошибки
     *
     * @param string $message
     * @param bool $showFile
     * @return void
     */
    public function error(string $message, bool $showFile = true): void
    {
        $this->caller['file'] = debug_backtrace()[1]['file'];
        $this->caller['line'] = debug_backtrace()[1]['line'];
        $this->log("ERROR - " . $message, $showFile);
    }

    /**
     * Обертка для создания лога дебага
     *
     * @param string $message
     * @param bool $showFile
     * @return void
     */
    public function debug(string $message, bool $showFile = true): void
    {
        $this->caller['file'] = debug_backtrace()[1]['file'];
        $this->caller['line'] = debug_backtrace()[1]['line'];
        $this->log("DEBUG - " . $message, $showFile);
    }

    /**
     * Создание лога
     *
     * @param string $message Сообщение для лога
     * @param bool $showFile показывать ли в логе файлов тот файл, в котором была вызвана функция
     * @return void
     */
    private function log(string $message, bool $showFile = false): void
    {
        $fileLine = "";
        if ($showFile) {
            $fileData = $this->caller;
            $fileName = str_replace(Application::$ROOT_PATH, "", $fileData['file']);
            $fileLine = "$fileName:{$fileData['line']} - ";
        }

        $content = file_get_contents($this->pathToLogFile);
        $log = "[" . date('Y-m-d H:i:s') . "] - $fileLine" . $message . PHP_EOL;
        if ($content == "")
            file_put_contents($this->pathToLogFile, $log);
        else
            file_put_contents($this->pathToLogFile, $content . "\n" . $log);
    }

}
