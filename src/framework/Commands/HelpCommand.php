<?php

namespace TetherPHP\framework\Commands;

use TetherPHP\framework\Modules\Console;

class HelpCommand extends Command
{
    public string $command = 'help';

    public string $description = 'Displays help';

    public function execute(): int
    {
        $console = new Console('');
        $this->info("Available commands:");

        foreach($console->commands as $command) {
            $commandInstance = new $command();
            $this->info(" - {$commandInstance->command} - {$commandInstance->description}");
        }

        return self::COMMAND_SUCCESS;
    }
}