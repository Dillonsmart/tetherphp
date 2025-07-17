<?php

use TetherPHP\Router;

return function (Router $router) {
    $router->get('/', Actions\ShowHomePageAction::class);

    $router->group('/docs', function(Router $router) {
        $router->get('', Actions\ShowDocsPageAction::class);
        $router->get('/requirements', Actions\ShowRequirementsPageAction::class);
    });
};