<?php

use Actions\Home;
use TetherPHP\Router;

return function (Router $router) {
    $router->get('/', Home::class);
};