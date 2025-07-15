<?php

namespace TetherPHP\Core\Interfaces;

interface ResponseInterface
{
    /**
     * Render a view with the given data.
     *
     * @param string $viewName The name of the view to render.
     * @param array $data Data to pass to the view.
     * @return string Rendered view content.
     */
    public function view(string $viewName, array $data = []): string;

    /**
     * Return a JSON response with the given data and status code.
     *
     * @param array $data Data to return in the JSON response.
     * @param int $statusCode HTTP status code for the response.
     * @return string JSON encoded data or false on failure.
     */
    public function json(array $data, int $statusCode): string;
}