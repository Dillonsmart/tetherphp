<?php

declare(strict_types=1);

namespace Domains;

class Home extends Domain
{
    public function handle(): array
    {
        return [
            'appName' => env('APP_NAME'),
            'tagline' => 'An application built with TetherPHP.',
        ];
    }
}
