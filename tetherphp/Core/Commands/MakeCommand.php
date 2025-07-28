<?php

namespace TetherPHP\Core\Commands;

use TetherPHP\Core\Traits\Strings;

class MakeCommand extends Command
{
    use Strings;

    public string $command = 'make:command';

    public string $description = 'Create a new command';

    public array $arguments = [
        'name' => 'The name of the command',
    ];

    public function execute()
    {
        $this->createCommandDirectory();

        $className = $this->toValidClassName($this->argument('name')) . 'Command';

        $commandFilePath = app_dir() . "/Commands/{$className}.php";

        if (file_exists($commandFilePath)) {
            $this->error("Command already exists: {$commandFilePath}");
            return self::COMMAND_ERROR;
        }

        $template = file_get_contents(core_dir() . '/Templates/Command.txt');
        $template = str_replace('{{className}}', $className, $template);

        if (file_put_contents($commandFilePath, $template) === false) {
            $this->error("Failed to create command file: {$commandFilePath}");
            return self::COMMAND_ERROR;
        }

        $this->success("Command created successfully: {$commandFilePath}");
        return self::COMMAND_SUCCESS;
    }

    public function createCommandDirectory(): int
    {
        $commandDir = app_dir() . '/Commands';

        if (!is_dir($commandDir)) {
            if (!mkdir($commandDir, 0755, true)) {
                $this->error("Failed to create directory: {$commandDir}");
                return self::COMMAND_ERROR;
            }
        }

        $this->success("Command directory created at: {$commandDir}");
        return self::COMMAND_SUCCESS;
    }
}