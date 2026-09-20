<?php

declare(strict_types=1);

namespace Domains\User\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * What a User write changed.
 *
 * Shared by every operation that changes something. It carries the identifier
 * rather than the record because the Responder answers with a redirect and the
 * browser asks for the record again — the second half of Post/Redirect/Get, so
 * loading it here would be work the response throws away.
 */
final readonly class Written implements DomainResult
{
    public function __construct(
        public string $id,
    ) {
    }
}
