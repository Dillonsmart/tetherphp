<?php

declare(strict_types=1);

namespace Domains;

use Domains\Results\Home as HomeResult;

class Home extends Domain
{
    public function handle(): HomeResult
    {
        return new HomeResult(
            name: env('APP_NAME'),
            description: 'An application built with TetherPHP.',
        );
    }
}
