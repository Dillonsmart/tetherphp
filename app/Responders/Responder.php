<?php

namespace Responders;

use TetherPHP\Core\Requests\Request;

class Responder
{
    public function __construct(protected Request $request)
    {
        // nothing to see here
    }

    protected function response(): static
    {
        return $this;
    }

    protected function view(string $viewName, array $data = []): string
    {
        $viewPath = str_replace('.', '/', $viewName);

        // Assuming the view is a file path, include it and capture the output
        ob_start();
        extract($data);

        $theView = views_dir() . "{$viewPath}.php";

        if(!file_exists($theView)) {
            include(core_views() . 'errors/viewNotFound.php');
            die();
        }

        include $theView;
        return ob_get_clean();
    }

    protected function json(array $data, int $statusCode): false|string
    {
        http_response_code($statusCode);
        return json_encode($data);
    }
}