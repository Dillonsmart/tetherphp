<?php

namespace TetherPHP;

use TetherPHP\Core\Modules\Env;
use TetherPHP\Core\Requests\Request;

class Kernel {

    protected Request $request;

    protected string $version = "0.1 alpha";

    public function __construct(protected Router $router) {
        Env::getInstance();

        $this->setErrorHandler();
    }

    public function run() {
        $this->request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        $action = $this->router->routeAction($this->request);

        if(!$action) {
            return '404';
        }

        $invokeAction = new $action($this->request);
        return $invokeAction();
    }

    private function setErrorHandler(): void
    {
        if(env('APP_DEBUG') === 'true') {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            \TetherPHP\Core\Modules\Log::error("Error [$errno]: $errstr in $errfile on line $errline");
            include(core_views() . 'errors/500.php');
        });

        set_exception_handler(function($exception) {
            \TetherPHP\Core\Modules\Log::error("Uncaught Exception: " . $exception->getMessage());
            \TetherPHP\Core\Modules\Log::error("Uncaught Exception: " . $exception->getTraceAsString());
            include(core_views() . 'errors/500.php');
        });
    }
}