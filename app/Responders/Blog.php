<?php

namespace Responders;

class Blog extends Responder
{
    public function __invoke(): string
    {
         return $this->response()->view('pages.blog.index');
    }
}