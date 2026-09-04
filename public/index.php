<?php

declare(strict_types=1);

use TetherPHP\Kernel;
use TetherPHP\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

(require __DIR__ . '/../routes/web.php')($router);

new Kernel($router)->run()->send();
