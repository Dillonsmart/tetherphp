<?php

use TetherPHP\Router;

/*
 * Where a request goes. `routes/middleware.php` says what it passes through.
 *
 * Every feature is a directory, so an Action is named by its feature and what
 * it does: `Actions\Home\Index` lives at `app/Actions/Home/Index.php`. Adding a
 * second page to the Home feature is `tether make:action Home <Operation>` and
 * one more line here.
 */
return function (Router $router) {
    $router->get('/', Actions\Home\Index::class);
};
