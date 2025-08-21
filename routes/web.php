<?php

use TetherPHP\Router;

return function (Router $router) {
    $router->view('/', 'pages.home.index');

    $router->group('/docs', function(Router $router) {
        $router->view('', 'pages.docs.index');
        $router->view('/adr', 'pages.docs.adr');
        $router->view('/requirements', 'pages.docs.requirements');
        $router->view('/usage', 'pages.docs.usage');
        $router->view('/routing', 'pages.docs.routing');
        $router->view('/responders', 'pages.docs.responders');
    });
};