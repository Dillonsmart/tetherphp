<?php

declare(strict_types=1);

namespace Domains\User\Results;

use TetherPHP\framework\Interfaces\DomainResult;

/**
 * One User, and its identity.
 *
 * Shared by every operation that answers with a single record — showing one,
 * editing one, and offering a blank one to create.
 *
 * The id is deliberately not just another key in $attributes. A form posts back
 * to a URL built from it, and a create form has attributes with no id yet: an
 * empty id is what tells the template it is making rather than editing.
 */
final readonly class Record implements DomainResult
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public string $id,
        public array $attributes,
    ) {
    }
}
