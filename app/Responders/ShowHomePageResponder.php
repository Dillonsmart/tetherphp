<?php

namespace Responders;

use DTOs\HomePageData;

class ShowHomePageResponder extends Responder
{
    public function __invoke(?array $data): string
    {
        $time = number_format((microtime(true) - $this->request->startTime) * 1000, 2);

        $dto = new HomePageData((float)$time);

        return $this->response()->view('pages.home.index', [
            'data' => $dto,
            'request' => $this->request,
        ]);
    }
}