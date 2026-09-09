<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The home page, end to end: routing, the Action, the Domain, the Responder
 * and the view, asserted on the Response the Kernel returns.
 *
 * Copy this for your own features — a feature test is the cheapest way to know
 * that a route, an Action and the view it renders still agree with each other.
 */
class HomeTest extends TestCase
{
    public function testTheHomePageIsServed(): void
    {
        $response = $this->get('/');

        $this->assertSame(200, $response->status());
    }

    public function testTheHomePageRendersTheApplicationName(): void
    {
        $this->assertStringContainsString('TetherPHP', $this->get('/')->body());
    }

    /**
     * The error view is served with a 200 whenever the status was never set,
     * so a status assertion alone would not catch a page that broke.
     */
    public function testTheHomePageIsNotTheErrorView(): void
    {
        $this->assertStringNotContainsString('500 Internal Server Error', $this->get('/')->body());
    }

    public function testAnUnknownPathIs404(): void
    {
        $this->assertSame(404, $this->get('/does-not-exist')->status());
    }
}
