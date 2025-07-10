<?php

namespace Responder\HomePage;

use Responder\Responder;

class ShowHomePageResponder extends Responder
{
    public function __invoke()
    {
        return $this->response()->view('pages.home.index', [
           'title' => 'Home Page',
        ]);
    }
}