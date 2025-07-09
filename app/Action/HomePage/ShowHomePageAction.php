<?php

namespace Action\HomePage;

use TetherPHP\Core\Requests\Request;

class ShowHomePageAction
{
    public function __construct(protected Request $request)
    {
        // nothing to see here
    }

    public function __invoke(): string
    {

//        $result = $this->domain->create();
//
//        return $this->responder->respond($result);

        // This method would typically render the home page view
        return "Welcome to the Home Page! This is the request method: " . $this->request->method . " and URI: " . $this->request->uri;
    }
}