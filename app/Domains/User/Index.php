<?php

declare(strict_types=1);

namespace Domains\User;

use Domains\Domain;
use Domains\User\Results\Collection;

class Index extends Domain
{
    public function handle(): Collection
    {
        // TODO: read the records to list.

        return new Collection([]);
    }
}
