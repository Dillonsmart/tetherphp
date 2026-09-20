<?php

declare(strict_types=1);

namespace Domains\User\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * Many User records: what a domain that read a set hands back.
 *
 * Shared by every User operation that answers with a list, because they
 * answer with the same thing. Name these properties for the domain, not for the
 * template — the Responder decides what a view is given.
 */
final readonly class Collection implements DomainResult
{
    /**
     * @param list<array<string, mixed>> $records
     */
    public function __construct(
        public array $records,
    ) {
    }
}
