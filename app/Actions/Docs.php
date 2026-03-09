<?php

namespace Actions;

use Domains\Docs as DocsDomain;
use Responders\Docs as DocsResponder;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class Docs extends Action implements ActionInterface
{
    public function __construct(protected Request $request)
    {
        $this->domain = new DocsDomain();
        $this->responder = new DocsResponder($request);
    }

    public function __invoke(): string
    {
        $page = $this->resolvePageSlug();
        $data = $this->domain->handle($page);
        return $this->respond($data);
    }

    private function resolvePageSlug(): string
    {
        $uri = trim($this->request->uri, '/');
        $segments = explode('/', $uri);

        return $segments[1] ?? 'index';
    }
}
