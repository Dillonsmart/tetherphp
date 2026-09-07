<?php

declare(strict_types=1);

namespace Actions;

use Domains\Domain;
use Responders\Responder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\DomainResult;

class Action
{
    protected Domain $domain;

    protected Responder $responder;

    protected function respond(DomainResult $result): Response
    {
        return ($this->responder)($result);
    }
}
