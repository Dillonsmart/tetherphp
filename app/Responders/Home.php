<?php

namespace Responders;

class Home extends Responder
{
    public function __invoke(): string
    {
         return $this->response()->view('pages.home.index');
    }
}