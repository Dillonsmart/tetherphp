<?php

namespace TetherPHP\Core\Commands;

class Command
{
    const int COMMAND_SUCCESS = 0;
    const int COMMAND_ERROR = 1;
    const int COMMAND_INVALID_ARGUMENT = 2;

    public string $command = '';

    public string $description = '';

    protected array $arguments = []; // listed in the order they are defined

    protected array $options = [];

    public function success(string $message): void
    {
        echo "\033[32m{$message}\033[0m";
    }

    public function error(string $message): void
    {
        echo "\033[31m{$message}\033[0m";
    }
}