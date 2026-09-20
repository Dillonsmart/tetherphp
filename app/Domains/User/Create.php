<?php

declare(strict_types=1);

namespace Domains\User;

use Domains\Domain;
use Domains\User\Results\Record;

class Create extends Domain
{
    public function handle(): Record
    {
        // TODO: return the empty attributes a new record starts with.

        return new Record('', []);
    }
}
