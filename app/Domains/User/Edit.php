<?php

declare(strict_types=1);

namespace Domains\User;

use Domains\Domain;
use Domains\User\Results\Record;

class Edit extends Domain
{
    /**
     * What this domain needs arrives through its constructor.
     *
     * Not through handle(): the base class declares `handle(): DomainResult`
     * with no parameters, and PHP will not let an override add a required one.
     * The Action reads it off the request and hands it over, which is the same
     * rule the Kernel follows with the Env and the Log.
     */
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function handle(): Record
    {
        // TODO: find the record identified by $this->id, or throw HttpNotFoundException.

        return new Record($this->id, []);
    }
}
