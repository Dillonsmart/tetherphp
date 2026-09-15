<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * A feature test that sends two requests builds two Kernels, and each installs
 * an error handler on top of the last. tearDown() used to restore them in the
 * order they were built, so the first found the second's handler on top,
 * declined to touch it, and PHPUnit reported every such test as risky. This
 * exists to keep the unwinding last-in, first-out.
 */
class TwoRequestsTest extends TestCase
{
    public function testTwoRequestsInOneTestLeaveNoHandlerBehind(): void
    {
        $this->get('/');
        $this->get('/');

        // the assertion is PHPUnit's own: the test must not be marked risky
        $this->assertTrue(true);
    }
}
