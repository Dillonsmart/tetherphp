<?php

declare(strict_types=1);

namespace Responders;

use TetherPHP\framework\Http\Response;

class Home extends Responder
{
    /**
     * @param array<string, mixed> $data
     */
    public function __invoke(array $data = []): Response
    {
        return $this->view('pages.home.index', $data);
    }
}
