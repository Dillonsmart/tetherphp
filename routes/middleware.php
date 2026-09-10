<?php

declare(strict_types=1);

use TetherPHP\framework\Middleware\OverridesMethod;
use TetherPHP\framework\Middleware\VerifyCsrfToken;
use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;
use TetherPHP\framework\Sessions\Session;

/*
 * What every request passes through, outermost first.
 *
 * `web.php` says where a request goes; this says what it goes through on the
 * way. Middleware wraps routing as well as the Action, so these run for a
 * request that goes on to 404 too.
 *
 * The framework starts no session, checks no CSRF token and honours no
 * `_method` field of its own accord. An application serving forms wants all
 * three, which is why the skeleton ships them; a token-authenticated API
 * deletes both lines and boots with no session.
 *
 * OverridesMethod goes first so that the CSRF middleware below logs the verb
 * the form asked for rather than the POST it arrived as. A browser form can
 * only send GET or POST, so without it the router's put(), patch() and
 * delete() routes are unreachable from a page.
 *
 * **Building a middleware must have no side effects.** `tether routes`,
 * `tether explain` and `tether context` build this list to report what runs
 * around a request, so a constructor that opens a connection or starts a
 * session does it from a terminal too. Do the work in `__invoke()`.
 */
return function (Env $env, Log $log): array {
    return [
        new OverridesMethod(),
        new VerifyCsrfToken(new Session(), $log),
    ];
};
