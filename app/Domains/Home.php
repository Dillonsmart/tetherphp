<?php

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
