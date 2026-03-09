<?php

namespace Domains;

class Home extends Domain
{
    public function handle(): array
    {
        return [
            'appName' => env('APP_NAME'),
            'tagline' => 'A redefined framework for PHP built on the Action-Domain-Responder pattern.',
        ];
    }
}
