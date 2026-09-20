<?php

declare(strict_types=1);

namespace Responders\User;

use Domains\User\Results\Record;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Edit extends Responder
{
    /**
     * The one place this page's view variables are named.
     *
     * The form is shown here with nothing wrong yet; the Responder for the
     * write it submits to shows the same view again, with errors, if the
     * write is refused.
     */
    public function __invoke(Record $result): Response
    {
        return $this->view('pages.user.edit', [
            'id' => $result->id,
            'attributes' => $result->attributes,
            'errors' => [],
        ]);
    }
}
