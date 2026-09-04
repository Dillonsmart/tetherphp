<?php

declare(strict_types=1);

namespace Actions;

use Domains\Domain;
use TetherPHP\framework\Interfaces\ResponderInterface;

class Action
{
    protected Domain $domain;

    protected ResponderInterface $responder;

    protected function respond(array $data = []): string
    {
        return ($this->responder)($data);
    }
}
