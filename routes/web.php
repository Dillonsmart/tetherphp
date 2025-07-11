<?php

use TetherPHP\Router;

return function (Router $router) {
    // Define the route for the home page
    $router->get('/', Actions\ShowHomePageAction::class);
};