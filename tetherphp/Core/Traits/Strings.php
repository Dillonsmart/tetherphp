<?php

namespace TetherPHP\Core\Traits;

trait Strings
{
    public function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    public function toValidClassName(string $string): string
    {
        return $this->toPascalCase($string);
    }
}