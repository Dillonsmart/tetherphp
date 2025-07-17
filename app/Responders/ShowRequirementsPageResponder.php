<?php

namespace Responders;

class ShowRequirementsPageResponder extends Responder
{
    public function __invoke(): string
    {
        return $this->response()->view('pages.docs.requirements');
    }
}