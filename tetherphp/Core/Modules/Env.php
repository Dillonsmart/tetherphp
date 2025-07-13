<?php

namespace TetherPHP\Core\Modules;

class Env
{
    protected static ?Env $instance = null;
    protected string $basePath = '';
    protected array $envVars = [];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->basePath = project_root();
        $this->loadEnv();
    }

    public static function getInstance(): Env
    {
        if (self::$instance === null) {
            self::$instance = new Env();
        }
        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    public function loadEnv(): void
    {
        $envFile = $this->basePath . '/.env';

        if(!file_exists($envFile)){
            throw new \Exception('Env file not found');
        }

        $envContent = file_get_contents($envFile);
        $lines = explode("\n", $envContent);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line && !str_starts_with($line, '#')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, "\"'");

                $this->envVars[$key] = $value;
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function getEnv(string $key)
    {
        if (array_key_exists($key, $this->envVars)) {
            return $this->envVars[$key];
        }

        throw new \Exception("Environment variable '{$key}' not found.");
    }
}