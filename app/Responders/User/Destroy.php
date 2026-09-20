<?php

declare(strict_types=1);

namespace Responders\User;

use Domains\User\Results\Written;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Destroy extends Responder
{
    /**
     * A write answers with a redirect, not a page.
     *
     * Post/Redirect/Get: the browser is sent to a URL it can safely ask for
     * again, so a refresh after saving does not submit the form a second time.
     * 303 rather than 302 because 303 is defined to turn the next request into
     * a GET whatever this one was — which is the point, and is not guaranteed
     * for a 302 after a PUT or a DELETE.
     */
    public function __invoke(Written $result): Response
    {
        return Response::redirect('/user', 303);
    }
}
