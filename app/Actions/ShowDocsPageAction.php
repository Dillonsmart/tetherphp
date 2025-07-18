<?php

namespace Actions;

use Responders\ShowDocsPageResponder;
use TetherPHP\Core\Interfaces\ResponseInterface;
use TetherPHP\Core\Requests\Request;

class ShowDocsPageAction extends Action
{
    public function __construct(protected Request $request)
    {
        $this->responder = new ShowDocsPageResponder($request);
    }

    public function __invoke()
    {
        return ($this->responder)();
    }
}