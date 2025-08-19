<?php

namespace TetherPHP;

use Responders\Responder;
use TetherPHP\Core\Modules\Env;
use TetherPHP\Core\Requests\Request;

class Kernel {

    protected Request $request;

    protected string $versionName = "0.1 alpha";
    protected float $versionNumber = 0.1;

    public float|string $startTime;

    public function __construct(protected Router $router) {
        $this->startTime = microtime(true);
        Env::getInstance();

        if(!defined('VERSION_NAME')) {
            define('VERSION_NAME', $this->versionName);
        }

        if(!defined('VERSION')) {
            define('VERSION', $this->versionNumber);
        }

        $this->setErrorHandler();
    }

    public function run() {
        $this->request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $this->startTime);

        $route = $this->router->routeAction($this->request);

        if(!$route) {
            include(core_views() . 'errors/404.php');
            exit(404);
        }

        $actionClass = $route['action'] ?? null;
        $params = $route['params'] ?? [];

        // todo - revisit how views are handled, as an invalid action will return a view
        // the router should probably return an array key of 'action' and 'type' to differentiate between a view and an action
        // if we are using a view, we can just return the view
        if(!class_exists($actionClass)) {
            return new Responder($this->request)->view($actionClass, []);
        }

        $invokeAction = new $actionClass($this->request);
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