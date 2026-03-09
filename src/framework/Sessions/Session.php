<?php

namespace TetherPHP\framework\Sessions;

class Session
{
    const int TIMEOUT = 1800; // 30 mins default - TODO make this configurable

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($this->isExpired()) {
            // Session was destroyed, so start a new one
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        $this->reinitialize();
    }

    public function reinitialize(): void
    {
        $this->startTime();
        $this->setSessionId();
        $this->updateLastActivity();
    }

    public function setSessionId(): void
    {
        if (!isset($_SESSION['SESSION_ID'])) {
            $_SESSION['SESSION_ID'] = session_id();
        }
    }

    public function getSessionId(): string
    {
        return $_SESSION['SESSION_ID'] ?? session_id();
    }

    public function startTime(): void
    {
        if (!isset($_SESSION['start_time'])) {
            $_SESSION['start_time'] = time();
        }
    }

    public function updateLastActivity(): void
    {
        $_SESSION['last_activity'] = time();
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    public function isExpired(): bool
    {
        $now = time();
        $startTime = $_SESSION['start_time'] ?? $now;
        $lastActivity = $_SESSION['last_activity'] ?? $now;
        $timeout = self::TIMEOUT;

        if (($now - $startTime > $timeout) || ($now - $lastActivity > $timeout)) {
            $this->destroy();
            return true;
        }

        return false;
    }
}