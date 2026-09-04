<?php

declare(strict_types=1);

namespace Responders;

class Home extends Responder
{
    public function __invoke(array $data = []): string
    {
        return $this->view('pages.home.index', $data);
    }
}
