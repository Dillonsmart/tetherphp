<?php

namespace TetherPHP\Core\Modules;

class Log
{
    public static function error(string $message): void
    {
        (new Log)->writeLog('error', $message);
    }

    public static function info(string $message): void
    {
        (new Log)->writeLog('info', $message);
    }

    public function writeLog(string $level, string $message): void
    {
        try {
            $logDir = storage_dir() . 'logs/';

            if(!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $today = date('Y-m-d');
            $timestamp = date('Y-m-d H:i:s');

            $logFile = storage_dir() . 'logs/'.$today.'.log';
            $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;

            if (!file_exists($logFile)) {
                file_put_contents($logFile, '');
            }

            file_put_contents($logFile, $logMessage, FILE_APPEND);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during logging
            echo "Failed to write log: " . $e->getMessage() . PHP_EOL;
        }

    }
}