<?php

declare(strict_types=1);

namespace Actions;

use Domains\Home as HomeDomain;
use Responders\Home as HomeResponder;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Home extends Action implements ActionInterface
{
    public function __construct(protected Request $request)
    {
        $this->domain = new HomeDomain();
        $this->responder = new HomeResponder($request);
    }

    public function __invoke(): string
    {
        $data = $this->domain->handle();
        return $this->respond($data);
    }
}
