<?php

declare(strict_types=1);

namespace Domains\Home\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * A page of Home, with no collection and no record behind it.
 *
 * A Result is named for its shape, not for the operation that returns it, so
 * every Home operation answering with a plain page shares this one. There are
 * only so many answers a domain gives, and a class per operation produced
 * several that differed by their name and nothing else.
 *
 * Named for the domain, not for the template. `app/Views/pages/home/index.php`
 * reads $appName and $tagline because `Responders\Home\Index` says so — rename
 * either of those variables and nothing in here changes.
 */
final readonly class Page implements DomainResult
{
    public function __construct(
        public string $name,
        public string $description,
    ) {
    }
}
