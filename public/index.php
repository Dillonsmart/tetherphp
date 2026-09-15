<?php

declare(strict_types=1);

use App\Services;
use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;
use TetherPHP\Kernel;
use TetherPHP\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * What the application is made of is built here, in the file that boots it,
 * rather than found by the framework. The questions a reader has — which
 * .env is in play, where do the logs go, is there a database — are answered
 * by these lines, in the order written. app/Services.php lists what exists.
 *
 * The Kernel is handed the whole object and reads the Env and the Log off it;
 * every Action is handed it too, and passes its Domain the pieces it needs.
 */
$services = new Services(
    env: Env::fromFile(__DIR__ . '/../.env'),
    log: new Log(__DIR__ . '/../storage/logs'),
);

$router = new Router();

(require __DIR__ . '/../routes/web.php')($router);

$middleware = (require __DIR__ . '/../routes/middleware.php')($services->env, $services->log);

new Kernel($router, $services, $middleware)->run()->send();
