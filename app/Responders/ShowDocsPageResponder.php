<?php

namespace Responders;

class ShowDocsPageResponder extends Responder
{
    public function __invoke(?array $data = null): string
    {
        return $this->response()->view('pages.docs.index');
    }
}