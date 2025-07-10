<?php

namespace Action\HomePage;

use Responder\HomePage\ShowHomePageResponder;
use TetherPHP\Core\Requests\Request;
use TetherPHP\Core\Responses\ViewResponse;

class ShowHomePageAction
{
    protected $responder;

    public function __construct(protected Request $request)
    {
        $this->responder = new ShowHomePageResponder($request);
    }

    public function __invoke()
    {
        return ($this->responder)();
    }
}