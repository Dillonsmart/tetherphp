<?php

namespace TetherPHP\framework\Helpers;

class Route
{
    public static function isActive(string $route): string
    {
        $currentRoute = $_SERVER['REQUEST_URI'];

        return $currentRoute === $route;
    }
}