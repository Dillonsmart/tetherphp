<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The one test that is about the protection rather than about a feature, so it
 * runs with the middleware the application actually boots with.
 *
 * Copy this shape whenever a test needs the real stack; the default is no
 * middleware so that ordinary feature tests do not have to mint a token first.
 */
class CsrfTest extends TestCase
{
    /** @return list<\TetherPHP\framework\Interfaces\MiddlewareInterface> */
    protected function middleware(): array
    {
        return (require __DIR__ . '/../../routes/middleware.php')($this->env(), $this->log());
    }

    public function testAWriteWithoutATokenIsRefused(): void
    {
        $this->assertSame(403, $this->post('/')->status());
    }

    public function testReadsAreNotChallenged(): void
    {
        $this->assertSame(200, $this->get('/')->status());
    }
}
