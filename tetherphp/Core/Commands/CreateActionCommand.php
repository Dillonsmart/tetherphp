<?php

namespace TetherPHP\Core\Commands;

use TetherPHP\Core\Traits\Strings;

class CreateActionCommand extends Command
{
    use Strings;

    public string $command = 'create:action';

    public string $description = 'Creates a new action';

    protected array $arguments = [
        'name' => 'The name of the action',
    ];

    public function execute()
    {
        $actionName = $this->argument('name');

        if (empty($actionName) || !is_string($actionName)) {
            $this->error("Action name cannot be empty.\n");
            return self::COMMAND_INVALID_ARGUMENT;
        }

        $className = $this->toValidClassName($actionName);

        $filePath = app_dir() . "/Actions/{$className}.php";

        if (file_exists($filePath)) {
            $this->error("Action file already exists: {$filePath}\n");
            return self::COMMAND_ERROR;
        }

        $template = <<<PHP
<?php

namespace Actions;

class {{className}} extends Action
{
    public function __construct()
    {
    }

    public function __invoke()
    {
    }
}
PHP;

        $template = str_replace(
            ['{{className}}'],
            [$className],
            $template
        );

        if (file_put_contents($filePath, $template) === false) {
            $this->error("Failed to create action file: {$filePath}\n");
            return self::COMMAND_ERROR;
        }

        $this->success("Action created successfully: {$filePath}\n");
        return self::COMMAND_SUCCESS;
    }
}