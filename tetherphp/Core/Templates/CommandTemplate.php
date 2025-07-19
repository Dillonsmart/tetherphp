<?php

namespace TetherPHP\Core\Templates;

class CommandTemplate
{
    protected string $command = 'tetherphp:command';

    protected string $description = 'A command template for TetherPHP';

    protected array $arguments = []; // listed in the order they are defined

    protected array $options = [];

    public function execute()
    {
        // command execution logic goes here
    }
}