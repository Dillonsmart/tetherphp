<?php

namespace TetherPHP\Core\Commands;

use TetherPHP\Core\Traits\Strings;

class MakeFeatureCommand extends Command
{
    use Strings;

    public string $command = 'make:feature';

    public string $description = 'Creates a new feature';

    protected array $arguments = [
        'name' => 'The name of the feature',
    ];

    private string $className;

    public function execute(): int
    {
        $featureName = $this->argument('name');

        if (empty($featureName)) {
            $this->error("Feature name cannot be empty.\n");
            return self::COMMAND_INVALID_ARGUMENT;
        }

        $this->className = $this->toValidClassName($featureName);

        $actionResult = $this->createAction();
        if ($actionResult !== self::COMMAND_SUCCESS) {
            return $actionResult;
        }

        $domainResult = $this->createDomain();
        if ($domainResult !== self::COMMAND_SUCCESS) {
            return $domainResult;
        }

        $responderResult = $this->createResponder();
        if ($responderResult !== self::COMMAND_SUCCESS) {
            return $responderResult;
        }

        $this->success("Feature '{$this->className}' created successfully.\n");

        return self::COMMAND_SUCCESS;
    }

    private function createAction(): int
    {
        $actionFilePath = app_dir() . "/Actions/{$this->className}.php";

        if (file_exists($actionFilePath)) {
            $this->error("Class already exists: {$actionFilePath}\n");
            return self::COMMAND_ERROR;
        }

        if (file_put_contents($actionFilePath, $this->generateTemplate('/Templates/Action.txt')) === false) {
            $this->error("Failed to create class: {$actionFilePath}\n");
            return self::COMMAND_ERROR;
        }

        $this->success("Action created successfully: {$actionFilePath}\n");
        return self::COMMAND_SUCCESS;
    }

    private function createDomain(): int
    {
        $domainFilePath = app_dir() . "/Domains/{$this->className}.php";

        if (file_exists($domainFilePath)) {
            $this->error("Class already exists: {$domainFilePath}\n");
            return self::COMMAND_ERROR;
        }

        if (file_put_contents($domainFilePath, $this->generateTemplate('/Templates/Domain.txt')) === false) {
            $this->error("Failed to create class: {$domainFilePath}\n");
            return self::COMMAND_ERROR;
        }

        $this->success("Domain created successfully: {$domainFilePath}\n");
        return self::COMMAND_SUCCESS;
    }

    private function createResponder(): int
    {
        $domainFilePath = app_dir() . "/Responders/{$this->className}.php";

        if (file_exists($domainFilePath)) {
            $this->error("Class already exists: {$domainFilePath}\n");
            return self::COMMAND_ERROR;
        }

        if (file_put_contents($domainFilePath, $this->generateTemplate('/Templates/Responder.txt')) === false) {
            $this->error("Failed to create class: {$domainFilePath}\n");
            return self::COMMAND_ERROR;
        }

        $this->success("Responder created successfully: {$domainFilePath}\n");
        return self::COMMAND_SUCCESS;
    }

    private function generateTemplate(string $template): string
    {
        $template = file_get_contents(core_dir() . $template);
        return str_replace(
            ['{{className}}'],
            [$this->className],
            $template
        );
    }
}