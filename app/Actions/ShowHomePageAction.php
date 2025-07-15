<?php

namespace Actions;

use Domains\ShowHomePageDomain;
use Responders\ShowHomePageResponder;
use TetherPHP\Core\Interfaces\ResponseInterface;
use TetherPHP\Core\Requests\Request;

class ShowHomePageAction
{
    protected ResponseInterface $responder;

    public function __construct(protected Request $request)
    {
        $this->responder = new ShowHomePageResponder($request);
    }

    public function __invoke()
    {
        $domain = new ShowHomePageDomain();
        return ($this->responder)($domain->getData());
    }
}