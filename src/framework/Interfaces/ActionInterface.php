<?php

namespace TetherPHP\framework\Interfaces;

interface ActionInterface
{
    public function __invoke(): string;
}