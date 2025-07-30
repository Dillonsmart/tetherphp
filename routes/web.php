<?php

use TetherPHP\Router;

return function (Router $router) {
    $router->view('/', 'pages.home.index');

    $router->group('/docs', function(Router $router) {
        $router->get('', Actions\ShowDocsPageAction::class);
        $router->get('/requirements', Actions\ShowRequirementsPageAction::class);
        $router->get('/usage', Actions\ShowUsagePageAction::class);
    });
};