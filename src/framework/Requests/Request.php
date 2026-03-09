<?php

namespace TetherPHP\framework\Requests;

use TetherPHP\framework\Interfaces\RequestInterface;
use TetherPHP\framework\Sessions\Session;

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

    public array $payload {
        get {
            return $this->payload;
        }
        set {
            $this->payload = $value;
        }
    }

    public float|string $startTime;

    protected string $csrfToken;

    /**
     * @throws \Exception
     */
    public function __construct(Session $session, string $method = '', string $uri = '', float|string $startTime = '')
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->startTime = $startTime ?: microtime(true);
        $this->csrfToken = $session->get('csrf_token');

        if(in_array(strtoupper($method), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->validateCsrfToken($_POST['csrf_token']);
        }
    }

    /**
     * @throws \Exception
     */
    public function validateCsrfToken(string $token): void
    {
        if(!hash_equals($this->csrfToken, $token)) {
            throw new \Exception('Invalid CSRF token');
        }
    }
}