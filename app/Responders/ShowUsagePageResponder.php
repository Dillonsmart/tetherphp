<?php

namespace Responders;

class ShowUsagePageResponder extends Responder
{
    public function __invoke(): string
    {
        return $this->response()->view('pages.docs.usage');
    }
}