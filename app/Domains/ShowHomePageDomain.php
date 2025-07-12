<?php

namespace Domains;

class ShowHomePageDomain extends Domain
{
    public function getData(): array
    {
        return [
            'title' => 'Home Page',
            'content' => 'Welcome to the home page!'
        ];
    }
}