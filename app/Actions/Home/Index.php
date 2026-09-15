<?php

declare(strict_types=1);

namespace Actions\Home;

use Actions\Action;
use App\Services;
use Domains\Home\Index as IndexDomain;
use Responders\Home\Index as IndexResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Index extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        // the Domain is handed the one thing it needs, not the services object
        $this->domain = new IndexDomain($services->env);
        $this->responder = new IndexResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
