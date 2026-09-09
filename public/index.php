<?php

declare(strict_types=1);

use TetherPHP\framework\Middleware\VerifyCsrfToken;
use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;
use TetherPHP\framework\Sessions\Session;
use TetherPHP\Kernel;
use TetherPHP\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

(require __DIR__ . '/../routes/web.php')($router);

/*
 * The Kernel is handed its environment and its log rather than finding them,
 * so the two questions a reader has — which .env is in play, and where do the
 * logs go — are answered here in the file that boots the application.
 */
$env = Env::fromFile(__DIR__ . '/../.env');
$log = new Log(__DIR__ . '/../storage/logs');

/*
 * Middleware runs around everything, outermost first, in the order written
 * here. The framework starts no session and checks no CSRF token of its own
 * accord: an application that wants both says so, and one that does not — a
 * token-authenticated API — deletes this line and boots without a session.
 */
$session = new Session();

$middleware = [
    new VerifyCsrfToken($session, $log),
];

new Kernel($router, $env, $log, $middleware)->run()->send();
