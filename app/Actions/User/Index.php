<?php

declare(strict_types=1);

namespace Actions\User;

use Actions\Action;
use App\Services;
use Domains\User\Index as IndexDomain;
use Responders\User\Index as IndexResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Index extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        // hand the Domain what it needs from the request and from $services
        $this->domain = new IndexDomain();
        $this->responder = new IndexResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
