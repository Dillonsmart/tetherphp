<?php

namespace TetherPHP\framework\DTOs;

use TetherPHP\framework\Interfaces\ActionInterface;

class RouteDTO
{
    public ActionInterface|string $action;
    public string $type;
    public array $params;
}