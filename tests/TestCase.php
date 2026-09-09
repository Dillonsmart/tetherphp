<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\MiddlewareInterface;
use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;
use TetherPHP\Kernel;
use TetherPHP\Router;

/**
 * A request sent through the real Kernel, with the real routes.
 *
 * The environment and the log are built here rather than read from disk, so a
 * test states the settings it depends on and never writes into storage/. That
 * is only possible because the Kernel is handed both instead of finding them.
 *
 * No middleware is composed in by default, so writes are not CSRF-challenged
 * and most tests stay a single call. Override middleware() to test against
 * what the application actually boots with.
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected Router $router;

    /** @var list<Kernel> */
    private array $kernels = [];

    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];

        $this->router = new Router();

        (require __DIR__ . '/../routes/web.php')($this->router);
    }

    protected function tearDown(): void
    {
        // the Kernel installs error handlers; leaving them on would leak one
        // pair per test into the rest of the suite
        foreach ($this->kernels as $kernel) {
            $kernel->restoreErrorHandlers();
        }

        $this->kernels = [];
    }

    protected function get(string $uri): Response
    {
        return $this->send('GET', $uri);
    }

    protected function post(string $uri): Response
    {
        return $this->send('POST', $uri);
    }

    protected function send(string $method, string $uri): Response
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;

        $kernel = new Kernel($this->router, $this->env(), $this->log(), $this->middleware());

        $this->kernels[] = $kernel;

        return $kernel->run();
    }

    /**
     * What runs around the request under test.
     *
     * Empty by default: a feature test that had to mint a CSRF token before it
     * could POST would be testing the middleware rather than the feature. To
     * exercise the real stack — in a test that is about the protection —
     * override this with the application's own declaration:
     *
     *     protected function middleware(): array
     *     {
     *         return (require __DIR__ . '/../routes/middleware.php')($this->env(), $this->log());
     *     }
     *
     * @return list<MiddlewareInterface>
     */
    protected function middleware(): array
    {
        return [];
    }

    protected function env(): Env
    {
        return new Env(['APP_NAME' => 'TetherPHP', 'APP_DEBUG' => 'false']);
    }

    protected function log(): Log
    {
        return new Log(sys_get_temp_dir() . '/tetherphp-test-logs');
    }
}
