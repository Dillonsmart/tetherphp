<?php

namespace TetherPHP;

use TetherPHP\Core\Requests\Request;

class Router {
    public array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public string $prefix = '';

    public string $uri = '';

    public string $action = '';

    public function view(string $uri, string $view): void {
        $this->routes['GET'][$this->makeUri($uri)] = [
            'action' => $view,
            'type' => 'view'
        ];
    }

    public function get(string $uri, string $action): void {
        $this->uri = $uri;
        $this->action = $action;

        $this->routes['GET'][$this->makeUri($uri)] = $this->buildRoute();
    }

    public function post(string $uri, string $action): void {
        $this->uri = $uri;
        $this->action = $action;

        $this->routes['POST'][$this->makeUri($uri)] = $this->buildRoute();
    }

    public function makeUri(string $uri): string {
        return $this->prefix . $uri;
    }

    public function buildRoute(): array
    {
        return [
            'action' => $this->action,
            'type' => $this->hasDynamicParts($this->uri) ? 'dynamic' : 'static',
            'parts' => $this->hasDynamicParts($this->uri) ? $this->handleDynamicParts($this->uri) : []
        ];
    }

    public function hasDynamicParts(string $uri): bool {
        return str_contains($uri, '{') && str_contains($uri, '}');
    }

    public function handleDynamicParts(string $uri) {
        $dynamicParts = explode('{', $uri);

        $validParts = [];

        foreach ($dynamicParts as $part) {
            if (str_contains($part, '}')) {
                $part = explode('}', $part)[0];

                if (empty($part)) {
                    throw new \InvalidArgumentException("Dynamic part cannot be empty.");
                }

                $validParts[] = $part;
            }
        }

        return $validParts;
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

    public function routeAction(Request $request): array|null {
        if(array_key_exists($request->uri, $this->routes[$request->method])) {
            return [
                'action' => $this->routes[$request->method][$request->uri]['action'] ?? null,
                'params' => [],
                'type' => $this->routes[$request->method][$request->uri]['type'] ?? 'static'
            ];
        }

        foreach ($this->routes[$request->method] as $uri => $route) {
            if ($route['type'] === 'dynamic') {
                $parts = explode('/', $uri);
                $requestParts = explode('/', $request->uri);

                if (count($parts) !== count($requestParts)) {
                    continue;
                }

                $params = [];
                $isMatch = true;

                foreach ($parts as $index => $part) {
                    if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                        $params[trim($part, '{}')] = $requestParts[$index];
                    } elseif ($part !== $requestParts[$index]) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    return [
                        'action' => $route['action'],
                        'params' => $params,
                        'type' => $this->routes[$request->method][$request->uri]['type'] ?? 'static'
                    ];
                }
            }
        }

        return null;
    }
}