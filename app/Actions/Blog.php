<?php

namespace Actions;

use Responders\Blog as Responder;
use TetherPHP\Core\Interfaces\ActionInterface;
use TetherPHP\Core\Requests\Request;

class Blog extends Action implements ActionInterface
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