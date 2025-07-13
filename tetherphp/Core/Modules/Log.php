<?php

namespace TetherPHP\Core\Modules;

class Log
{
    private const string LOG_DIR = 'logs/';

    public static function error(string $message): void
    {
        self::writeLog('error', $message);
    }

    public static function info(string $message): void
    {
        self::writeLog('info', $message);
    }

    private static function writeLog(string $level, string $message): void
    {
        try {
            $logDir = storage_dir() . self::LOG_DIR;

            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $timestamp = date('Y-m-d H:i:s');
            $logFile = $logDir . date('Y-m-d') . '.log';
            $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;

            file_put_contents($logFile, $logMessage, FILE_APPEND);
        } catch (\Exception $e) {
            error_log("Failed to write log: " . $e->getMessage());
        }
    }
}