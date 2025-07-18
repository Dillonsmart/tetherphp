<?php

namespace TetherPHP;

use TetherPHP\Core\Requests\Request;

class Router {
    public array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public string $prefix = '';

    public function get(string $uri, string $action): void {
        $this->routes['GET'][$this->makeUri($uri)] = $action;
    }

    public function post(string $uri, string $action): void {
        $this->routes['POST'][$this->makeUri($uri)] = $action;
    }

    public function makeUri(string $uri): string {
        return $this->prefix . $uri;
    }

    public function group(string $prefix, callable $callback): void {
        if (empty($prefix)) {
            throw new \InvalidArgumentException("Prefix cannot be empty.");
        }

        if ($prefix[0] !== '/') {
            $prefix = '/' . $prefix;
        }

        $this->prefix = $prefix;

        $originalRoutes = $this->routes;
        $this->routes = [
            'GET' => [],
            'POST' => []
        ];

        $callback($this);

        foreach ($this->routes as $method => $uris) {
            foreach ($uris as $uri => $action) {
                $this->routes[$method]["{$uri}"] = $action;
            }
        }


        $this->routes['GET'] = array_merge($originalRoutes['GET'], $this->routes['GET']);
        $this->routes['POST'] = array_merge($originalRoutes['POST'], $this->routes['POST']);
        $this->prefix = '';
    }

    public function routeAction(Request $request) {
        return $this->routes[$request->method][$request->uri] ?? null;
    }
}