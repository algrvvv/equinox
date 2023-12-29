<?php

namespace Imissher\Equinox\app\core\Helpers;

trait MessageLogTrait
{
    private function messageLog(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] - " . $message . PHP_EOL;
    }
}