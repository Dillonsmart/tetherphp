<?php

declare(strict_types=1);

namespace Domains\Home;

use Domains\Domain;
use Domains\Home\Results\Page;
use TetherPHP\framework\Modules\Env;

class Index extends Domain
{
    /**
     * The environment arrives through the constructor rather than env(), so
     * this class depends on nothing that was not handed to it and a unit test
     * builds one with `new Env([...])` and no global in sight.
     */
    public function __construct(private readonly Env $env)
    {
    }

    public function handle(): Page
    {
        return new Page(
            name: $this->env->get('APP_NAME', 'TetherPHP'),
            description: 'An application built with TetherPHP.',
        );
    }
}
