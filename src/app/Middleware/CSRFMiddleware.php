<?php

namespace App\Middleware;

class CSRFMiddleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'], $token)) {
                http_response_code(403);
                die("Erreur CSRF : Jeton invalide ou manquant.");
            }
        }
    }
}
