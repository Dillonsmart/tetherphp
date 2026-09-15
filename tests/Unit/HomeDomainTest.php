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
 *
 * It needs no global either. The Domain takes its Env through the constructor,
 * so each test hands it exactly the environment it is about; this used to
 * call Env::use() in setUp() because the Domain read env() from the air.
 */
class HomeDomainTest extends TestCase
{
    private function domain(array $vars): HomeDomain
    {
        return new HomeDomain(new Env($vars));
    }

    public function testItReturnsItsOwnResultType(): void
    {
        $this->assertInstanceOf(HomeResult::class, $this->domain(['APP_NAME' => 'Test App'])->handle());
    }

    public function testItNamesTheApplicationFromTheEnvironment(): void
    {
        $this->assertSame('Test App', $this->domain(['APP_NAME' => 'Test App'])->handle()->name);
    }

    /**
     * A default keeps a missing APP_NAME from being a TypeError on a string
     * property. Env::get() returns null for a key that is not set.
     */
    public function testItFallsBackWhenTheApplicationIsUnnamed(): void
    {
        $this->assertSame('TetherPHP', $this->domain([])->handle()->name);
    }
}
