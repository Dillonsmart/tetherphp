<?php

use TetherPHP\Router;

return function (Router $router) {
    $router->get('/', Actions\ShowHomePageAction::class);
    $router->get('/docs', Actions\ShowDocsPageAction::class);
};