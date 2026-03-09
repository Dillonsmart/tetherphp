<?php

use Actions\Home;
use Actions\Docs;
use TetherPHP\Router;

return function (Router $router) {
    $router->get('/', Home::class);
    $router->get('/docs', Docs::class);
    $router->get('/docs/{page}', Docs::class);
};
