<?php

namespace TetherPHP;

use TetherPHP\Core\Requests\Request;

class Kernel {

    protected Request $request;

    public function __construct(protected Router $router) {
        // nothing to see here
    }

    public function run() {
        $this->request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        $action = $this->router->find($this->request);

        if(!$action) {
            return '404';
        }

        $invokeAction = new $action($this->request);
        return $invokeAction();
    }
}