<?php

namespace App\Core;

class App
{
    private $router;

    public function __construct(Router $router)
    {
        // Session security hardening
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_samesite', 'Lax');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize CSRF token
        \App\Helpers\CsrfHelper::generateToken();

        $this->router = $router;
    }

    public function run()
    {
        // CSRF Verification for state-changing methods
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? null;
            if (!$token) {
                // Check JSON input
                $input = json_decode(file_get_contents('php://input'), true);
                $token = $input['csrf_token'] ?? null;
            }

            if (!\App\Helpers\CsrfHelper::verifyToken($token)) {
                http_response_code(403);
                echo "Invalid CSRF Token. Please refresh the page.";
                exit;
            }
        }

        // Security Headers
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;");

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
