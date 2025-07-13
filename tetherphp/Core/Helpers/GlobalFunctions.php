<?php

function project_root(): string
{
    return dirname(__DIR__, 3);
}

function app_dir(): string
{
    return project_root() . '/app/';
}

function views_dir(): string
{
    return app_dir() . 'Views/';
}

function public_dir(): string
{
    return project_root() . '/public/';
}

function core_dir(): string
{
    return project_root() . '/tetherphp/Core';
}

function core_views(): string
{
    return core_dir() . '/Views/';
}

function view(string $view)
{
    return include views_dir() . '/' . $view . '.php';
}