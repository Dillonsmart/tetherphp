<?php

namespace TetherPHP;

use TetherPHP\Core\Requests\Request;

class Router {
    protected array $routes = [];

    public function get(string $uri, string $action): void {
        $this->routes['GET'][$uri] = $action;
    }

    public function find(Request $request) {
        return $this->routes[$request->method][$request->uri];
    }
}