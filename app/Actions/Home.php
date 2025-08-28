<?php

namespace Actions;

use Responders\Home as Responder;
use TetherPHP\framework\Requests\Request;
use TetherPHP\framework\Interfaces\ActionInterface;

class Home extends Action implements ActionInterface
{
    public function __construct(protected Request $request)
    {
        $this->responder = new Responder($request);
    }

    public function __invoke()
    {
        return ($this->responder)();
    }
}