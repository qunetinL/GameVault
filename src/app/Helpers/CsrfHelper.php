<?php

namespace App\Helpers;

class CsrfHelper
{
    /**
     * Generate a new CSRF token and store it in the session if not exists.
     */
    public static function generateToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Get the current CSRF token.
     */
    public static function getToken()
    {
        return $_SESSION['csrf_token'] ?? self::generateToken();
    }

    /**
     * Verify the provided token against the session token.
     */
    public static function verifyToken($token)
    {
        $sessionToken = self::getToken();
        return $token && hash_equals($sessionToken, $token);
    }

    /**
     * Output a hidden CSRF input field for forms.
     */
    public static function insertField()
    {
        $token = self::getToken();
        echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
