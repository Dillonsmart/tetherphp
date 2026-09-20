<?php

declare(strict_types=1);

namespace Responders\User;

use Domains\User\Results\Invalid;
use Domains\User\Results\Written;
use Responders\Responder;
use TetherPHP\framework\Http\Response;

class Update extends Responder
{
    /**
     * A write answers with a redirect; a refused one answers with the form.
     *
     * Post/Redirect/Get: the browser is sent to a URL it can safely ask for
     * again, so a refresh after saving does not submit the form a second time.
     * 303 rather than 302 because 303 is defined to turn the next request into
     * a GET whatever this one was — which is the point, and is not guaranteed
     * for a 302 after a PUT or a DELETE.
     *
     * A refusal renders the form again, with what was typed and what was wrong
     * beside each field, as a 422. No flash session and no redirect back: the
     * status and the view both come off the type of the result.
     */
    public function __invoke(Written|Invalid $result): Response
    {
        if ($result instanceof Invalid) {
            return $this->view('pages.user.edit', [
                'id' => $result->id,
                'attributes' => $result->attributes,
                'errors' => $result->errors,
            ], 422);
        }

        return Response::redirect('/user/' . $result->id, 303);
    }
}
