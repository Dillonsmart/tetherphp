<?php

declare(strict_types=1);

use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;
use TetherPHP\Kernel;
use TetherPHP\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * The Kernel is handed its environment and its log rather than finding them,
 * so the two questions a reader has — which .env is in play, and where do the
 * logs go — are answered here in the file that boots the application.
 */
$env = Env::fromFile(__DIR__ . '/../.env');
$log = new Log(__DIR__ . '/../storage/logs');

$router = new Router();

(require __DIR__ . '/../routes/web.php')($router);

$middleware = (require __DIR__ . '/../routes/middleware.php')($env, $log);

new Kernel($router, $env, $log, $middleware)->run()->send();
