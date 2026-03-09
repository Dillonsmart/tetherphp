<?php

namespace TetherPHP\framework\Sessions;

class CsrfToken
{
    /**
     * @throws \Exception
     */
    public function __construct(Session $session)
    {
        if(!$session->get('csrf_token')) {
            $this->generateToken($session);
        }
    }

    /**
     * @throws \Exception
     */
    public function generateToken(Session $session): void
    {
        try {
            $token = bin2hex(random_bytes(32));
        } catch (\Exception $e) {
            throw new \Exception('Could not generate CSRF token: ' . $e->getMessage());
        }

        $session->set('csrf_token', $token);
    }
}