<?php

namespace TetherPHP\Core\Commands;

class Command
{ // TODO when the command is executed we need to get the arguments and options from the command line input
    const int COMMAND_SUCCESS = 0;
    const int COMMAND_ERROR = 1;
    const int COMMAND_INVALID_ARGUMENT = 2;

    public string $command = '';

    public string $description = '';

    protected array $arguments = [];

    public function __construct(public array $args = [], public array $opts = [])
    {}

    public function success(string $message): void
    {
        echo "\033[32m{$message}\033[0m";
    }

    public function error(string $message): void
    {
        echo "\033[31m{$message}\033[0m";
    }

    public function argument(string $name): string|\Exception
    {
        if (array_key_exists($name, $this->arguments)) {
            $index = array_search($name, array_keys($this->arguments));
            return $this->args[$index] ?? '';
        }

        throw new \InvalidArgumentException("Argument '{$name}' not found in command '{$this->command}'.");
    }
}