<?php

declare(strict_types=1);

namespace Domains;

use TetherPHP\framework\Interfaces\DomainResult;

abstract class Domain
{
    abstract public function handle(): DomainResult;
}
