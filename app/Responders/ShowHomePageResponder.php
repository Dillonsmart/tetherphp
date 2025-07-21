<?php

namespace Responders;


use DTOs\HomePageData;

class ShowHomePageResponder extends Responder
{
    public function __invoke(HomePageData $data): string
    {
        $data->time = number_format((microtime(true) - $this->request->startTime) * 1000, 2);

        return $this->response()->view('pages.home.index', [
            'data' => $data,
            'request' => $this->request,
        ]);
    }
}