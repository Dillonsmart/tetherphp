<?php

namespace Responders;

class Docs extends Responder
{
    public function __invoke(array $data = []): string
    {
        if (!$data['found']) {
            http_response_code(404);
            return $this->view('pages.docs.index', $data);
        }

        return $this->view($data['view'], $data);
    }
}
