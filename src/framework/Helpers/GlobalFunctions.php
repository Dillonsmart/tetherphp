<?php

use Composer\InstalledVersions;

function project_root(): string
{
    $rootPackage = InstalledVersions::getRootPackage();
    // path to the root project

    return $rootPackage['install_path'];
}

function package_root(): string
{
    return dirname(__DIR__, 3);
}

function app_dir(): string
{
    return project_root() . '/app';
}

function storage_dir(): string
{
    return project_root() . '/storage/';
}

function views_dir(): string
{
    return app_dir() . '/Views/';
}

function public_dir(): string
{
    return project_root() . '/public/';
}

function core_dir(): string
{
    return package_root() . '/src/framework';
}

function core_views(): string
{
    return core_dir() . '/Views/';
}

function view(string $view)
{
    return include views_dir() . '/' . $view . '.php';
}

function env(string $key): ?string
{
    $env = \TetherPHP\framework\Modules\Env::getInstance();
    try {
        return $env->getEnv($key);
    } catch (\Exception $e) {
        logger($e, 'error');;
        return null;
    }
}

function logger(string $message, string $level = 'info'): void
{
    if ($level === 'error') {
        \TetherPHP\framework\Modules\Log::error($message);
    } else {
        \TetherPHP\framework\Modules\Log::info($message);
    }
}