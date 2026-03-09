<?php

namespace Responders;

use TetherPHP\framework\Interfaces\ResponderInterface;
use TetherPHP\framework\Requests\Request;

class Responder implements ResponderInterface
{
    public function __construct(protected Request $request)
    {
    }

    public function view(string $viewName, array $data = []): string
    {
        $viewPath = str_replace('.', '/', $viewName);
        $file = views_dir() . "{$viewPath}.php";

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$viewName}");
        }

        ob_start();
        extract($data);
        include $file;
        return ob_get_clean();
    }

    public function json(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
