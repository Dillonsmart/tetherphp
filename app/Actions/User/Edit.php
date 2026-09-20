<?php

declare(strict_types=1);

namespace Actions\User;

use Actions\Action;
use App\Services;
use Domains\User\Edit as EditDomain;
use Responders\User\Edit as EditResponder;
use TetherPHP\framework\Http\Response;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Edit extends Action implements ActionInterface
{
    public function __construct(protected Request $request, Services $services)
    {
        // hand the Domain what it needs from the request and from $services
        $this->domain = new EditDomain($request->params['id'] ?? '');
        $this->responder = new EditResponder($request);
    }

    public function __invoke(): Response
    {
        return $this->respond($this->domain->handle());
    }
}
