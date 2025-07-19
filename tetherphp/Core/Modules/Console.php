<?php

namespace TetherPHP\Core\Modules;

use TetherPHP\Core\Commands\Command;

class Console
{
    public array $commands = [];

    public function __construct(public string $command)
    {
        if(empty($this->command)) {
            echo "No command provided.\n";
            exit(Command::COMMAND_INVALID_ARGUMENT);
        }

        $this->registerCommands();
    }

    public function registerCommands(): void
    {
        $tetherCommands =  [
            ...glob(__DIR__ . "/../Commands/*.php"),
        ];

        $customCommands = [
            // todo - implement custom commands
        ];

        foreach ($tetherCommands as $commandClass) {
            $className = 'TetherPHP\\Core\\Commands\\' . basename($commandClass, '.php');

            if (class_exists($className) && is_subclass_of($className, Command::class)) {
                $commandInstance = new $className();
                $this->commands[$commandInstance->command] = $className;
            }
        }

        // TODO - implement custom commands
    }

    public function executeCommand(array $args = [], array $options = []): int
    {
        if (isset($this->commands[$this->command])) {
            $commandInstance = new $this->commands[$this->command]();
            return $commandInstance->execute();
        }

        return Command::COMMAND_INVALID_ARGUMENT;
    }
}