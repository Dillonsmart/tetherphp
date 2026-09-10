<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domains\Home\Index as HomeDomain;
use Domains\Home\Results\Page as HomeResult;
use PHPUnit\Framework\TestCase;
use TetherPHP\framework\Modules\Env;

/**
 * A Domain knows nothing about HTTP, so testing one needs no Kernel, no
 * routing and no request — which is the whole point of keeping them separate.
 */
class HomeDomainTest extends TestCase
{
    protected function setUp(): void
    {
        // env() delegates to whatever was installed at boot; a unit test
        // installs its own rather than reading the .env on disk
        Env::use(new Env(['APP_NAME' => 'Test App']));
    }

    public function testItReturnsItsOwnResultType(): void
    {
        $this->assertInstanceOf(HomeResult::class, new HomeDomain()->handle());
    }

    public function testItNamesTheApplicationFromTheEnvironment(): void
    {
        $this->assertSame('Test App', new HomeDomain()->handle()->name);
    }

    /**
     * A default keeps a missing APP_NAME from being a TypeError on a string
     * property. env() returns null for a key that is not set.
     */
    public function testItFallsBackWhenTheApplicationIsUnnamed(): void
    {
        Env::use(new Env([]));

        $this->assertSame('TetherPHP', new HomeDomain()->handle()->name);
    }
}
