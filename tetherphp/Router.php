<?php

namespace TetherPHP;

use TetherPHP\Core\Requests\Request;

class Router {
    protected array $routes = [];

    public function get(string $uri, string $action): void {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, string $action): void {
        $this->routes['POST'][$uri] = $action;
    }

    public function group(string $prefix, callable $callback): void {
        $originalRoutes = $this->routes;
        $this->routes = [];

        $callback($this);

        foreach ($this->routes as $method => $uris) {
            foreach ($uris as $uri => $action) {
                $this->routes[$method]["{$prefix}{$uri}"] = $action;
            }
        }

        $this->routes = array_merge($originalRoutes, $this->routes);
    }

    public function routeAction(Request $request) {
        return $this->routes[$request->method][$request->uri];
    }
}