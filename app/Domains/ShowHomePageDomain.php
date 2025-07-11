<?php

namespace Domains;

class ShowHomePageDomain extends Domain
{
    public function getData(): array
    {
        print_r('Executing ShowHomePageDomain domain logic...');
        return [
            'title' => 'Home Page',
            'content' => 'Welcome to the home page!'
        ];
    }
}