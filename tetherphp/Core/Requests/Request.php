<?php

namespace TetherPHP\Core\Requests;

use TetherPHP\Core\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    public string $method {
        get {
            return $this->method;
        }
        set {
            $this->method = strtoupper($value);
        }
    }
    public string $uri {
        get {
            return $this->uri;
        }
        set {
            $this->uri = strtolower($value);
        }
    }

    public function __construct(string $method = '', string $uri = '')
    {
        $this->method = $method;
        $this->uri = $uri;
    }
}