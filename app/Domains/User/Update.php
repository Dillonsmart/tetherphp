<?php

declare(strict_types=1);

namespace Domains\User;

use Domains\Domain;
use Domains\User\Results\Invalid;
use Domains\User\Results\Written;

class Update extends Domain
{
    /**
     * What this domain needs arrives through its constructor.
     *
     * Not through handle(): the base class declares `handle(): DomainResult`
     * with no parameters, and PHP will not let an override add a required one.
     * The Action reads it off the request and hands it over, which is the same
     * rule the Kernel follows with the services.
     */
    public function __construct(
        private readonly string $id,
        /** @var array<string, mixed> */
        private readonly array $payload,
    ) {
    }

    /**
     * One result type per outcome: the write that happened, or the refusal
     * with what was sent and why. The Responder tells them apart by type.
     */
    public function handle(): Written|Invalid
    {
        $attributes = Attributes::fromPayload($this->payload);

        if (!$attributes->isValid()) {
            return new Invalid($this->id, $attributes->values, $attributes->errors);
        }

        // TODO: apply $attributes->values to the record identified by $this->id.

        return new Written($this->id);
    }
}
