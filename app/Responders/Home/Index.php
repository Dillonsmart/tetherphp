<?php

declare(strict_types=1);

namespace Responders\Home;

use Domains\Home\Results\Page;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Index extends Responder
{
    /**
     * The one place this page's view variables are named.
     */
    public function __invoke(Page $result): Response
    {
        return $this->view('pages.home.index', [
            'appName' => $result->name,
            'tagline' => $result->description,
        ]);
    }
}
