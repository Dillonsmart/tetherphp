<?php

declare(strict_types=1);

namespace Domains\Home;

use Domains\Domain;
use Domains\Home\Results\Page;

class Index extends Domain
{
    public function handle(): Page
    {
        return new Page(
            name: env('APP_NAME', 'TetherPHP'),
            description: 'An application built with TetherPHP.',
        );
    }
}
