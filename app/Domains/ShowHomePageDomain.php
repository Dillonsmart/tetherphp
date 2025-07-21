<?php

namespace Domains;

use DTOs\HomePageData;

class ShowHomePageDomain extends Domain
{
    public function getData(): HomePageData
    {
        return new HomePageData(
            [
                'title' => 'Home Page'
            ]
        );
    }
}