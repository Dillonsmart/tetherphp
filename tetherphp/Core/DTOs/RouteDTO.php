<?php

namespace TetherPHP\Core\DTOs;

use TetherPHP\Core\Interfaces\ActionInterface;

class RouteDTO
{
    public ActionInterface|string $action;
    public string $type;
    public array $params;
}