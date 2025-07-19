<?php

namespace TetherPHP\Core\Commands;

class ClearBoilerPlateCommand extends Command
{

    public string $command = 'boilerplate:clear';

    public string $description = 'Clears all the boilerplate files from the project';

    protected array $arguments = []; // listed in the order they are defined

    protected array $options = [];

    public function execute()
    {
        // TODO - implement
        print_r("Clearing boilerplate files...\n");
        exit(0);
    }
}