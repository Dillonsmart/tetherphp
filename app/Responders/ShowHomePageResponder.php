<?php

namespace Responders;

class ShowHomePageResponder extends Responder
{
    public function __invoke(?array $data): string
    {
        $data['time'] = microtime(true) - $this->request->startTime;

        return $this->response()->view('pages.home.index', $data);
    }
}