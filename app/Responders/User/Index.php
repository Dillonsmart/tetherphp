<?php

declare(strict_types=1);

namespace Responders\User;

use Domains\User\Results\Collection;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Index extends Responder
{
    /**
     * The one place this page's view variables are named.
     *
     * `records` is what the generator could call it, not what the page should:
     * rename it here for what the list actually is, and the template follows.
     */
    public function __invoke(Collection $result): Response
    {
        return $this->view('pages.user.index', [
            'records' => $result->records,
            'query' => $this->request->query,
        ]);
    }
}
