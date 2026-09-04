<?php

declare(strict_types=1);

namespace Responders;

use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ResponderInterface;
use TetherPHP\framework\Requests\Request;

class Responder implements ResponderInterface
{
    public function __construct(protected Request $request)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function view(string $viewName, array $data = [], int $status = 200): Response
    {
        $viewPath = str_replace('.', '/', $viewName);
        $file = views_dir() . "{$viewPath}.php";

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$viewName}");
        }

        return Response::html($this->renderInIsolation($file, $data), $status);
    }

    /**
     * Renders a view with $data in scope.
     *
     * The extraction happens inside a closure holding nothing worth clobbering,
     * because extract() in the calling scope could rebind $file *after* it had
     * been checked — view data containing a 'file' key would then include an
     * arbitrary path. EXTR_SKIP additionally refuses to overwrite what is there.
     *
     * @param array<string, mixed> $data
     */
    private function renderInIsolation(string $__file, array $__data): string
    {
        $render = static function () use ($__file, $__data): string {
            extract($__data, EXTR_SKIP);
            unset($__data);

            ob_start();
            include $__file;

            return (string) ob_get_clean();
        };

        return $render();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function json(array $data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }
}
