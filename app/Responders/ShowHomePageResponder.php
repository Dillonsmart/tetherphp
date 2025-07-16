<?php

namespace Responders;

class ShowHomePageResponder extends Responder
{
    public function __invoke(?array $data): string
    {
        return $this->response()->view('pages.home.index', $data);
    }
}