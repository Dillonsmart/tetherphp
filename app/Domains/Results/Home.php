<?php

declare(strict_types=1);

namespace Domains\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * What the Home domain returns.
 *
 * Named for the domain, not for the template. `app/Views/pages/home/index.php`
 * reads $appName and $tagline because Responders\Home says so — rename either
 * of those variables and nothing in here changes.
 */
final readonly class Home implements DomainResult
{
    public function __construct(
        public string $name,
        public string $description,
    ) {
    }
}
