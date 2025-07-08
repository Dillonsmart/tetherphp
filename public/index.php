<?php

use TetherPHP\Kernel;
use TetherPHP\Router;

require_once "../vendor/autoload.php";

$router = new Router();

(require __DIR__ . '/../routes/web.php')($router);

$kernel = new Kernel($router);

$response = $kernel->run();

print_r($response);