<?php

namespace TetherPHP\Core\Modules;

use TetherPHP\Core\Commands\Command;

class Console
{
    public array $commands = [];

    public function __construct(public string $command)
    {
        $this->registerCommands();
    }

    public function registerCommands(): void
    {
        $tetherCommands =  [
            ...glob(__DIR__ . "/../Commands/*.php"),
        ];

        $customCommands = [
            ...glob(app_dir() . "/Commands/*.php"),
        ];

        foreach ($tetherCommands as $commandClass) {
            $className = 'TetherPHP\\Core\\Commands\\' . basename($commandClass, '.php');

            $this->addCommand($className);
        }

        foreach ($customCommands as $commandClass) {
            $className = 'Commands\\' . basename($commandClass, '.php');

            $this->addCommand($className);
        }
    }

    private function addCommand(string $className): void
    {
        if (class_exists($className) && is_subclass_of($className, Command::class)) {
            $commandInstance = new $className();
            $this->commands[$commandInstance->command] = $className;
        }
    }

    public function executeCommand(array $args = [], array $options = []): int
    {
        if (isset($this->commands[$this->command])) {
            $commandInstance = new $this->commands[$this->command]($args, $options);
            return $commandInstance->execute();
        }

        // TODO - If command methods are going to be called directly, maybe consider a shared trait.
        (new \TetherPHP\Core\Commands\Command)->error("Command {$this->command} not found.");
        return Command::COMMAND_ERROR;
    }
}