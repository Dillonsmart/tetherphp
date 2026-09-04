<?php

declare(strict_types=1);

namespace Actions;

use Domains\Domain;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Action
{
    protected Domain $domain;

    protected Responder $responder;

    /**
     * @param array<string, mixed> $data
     */
    protected function respond(array $data = []): Response
    {
        return ($this->responder)($data);
    }
}
