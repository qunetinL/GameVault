<?php

namespace App\Core;

class App
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        // Session initialization
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_samesite' => 'Lax'
            ]);
        }

        // Global CSRF Token generation
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Security Headers
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;");

        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $code = $e->getCode();
            // http_response_code only accepts integers (4xx/5xx)
            if (!is_int($code) || $code < 100 || $code > 599) {
                $code = 500;
            }
            http_response_code($code);
            echo $e->getMessage();
        }
    }
}
