<?php

declare(strict_types=1);

namespace Responders;

use Domains\Results\Home as HomeResult;
use TetherPHP\framework\Http\Response;

class Home extends Responder
{
    /**
     * The one place this page's view variables are named.
     */
    public function __invoke(HomeResult $result): Response
    {
        return $this->view('pages.home.index', [
            'appName' => $result->name,
            'tagline' => $result->description,
        ]);
    }
}
