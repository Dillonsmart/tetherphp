<?php

namespace Actions;

use Responders\ShowRequirementsPageResponder;
use TetherPHP\Core\Requests\Request;

class ShowRequirementsPageAction extends Action
{
    public function __construct(protected Request $request)
    {
        $this->responder = new ShowRequirementsPageResponder($request);
    }

    public function __invoke()
    {
        return ($this->responder)();
    }
}