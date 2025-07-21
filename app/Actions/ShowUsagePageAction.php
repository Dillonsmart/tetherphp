<?php

namespace Actions;

use Responders\ShowUsagePageResponder;
use TetherPHP\Core\Requests\Request;

class ShowUsagePageAction extends Action
{
    public function __construct(protected Request $request)
    {
        $this->responder = new ShowUsagePageResponder($request);
    }

    public function __invoke()
    {
        return ($this->responder)();
    }
}