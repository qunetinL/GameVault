<?php

namespace App\Middleware;

use App\Models\User;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Vérifier que l'utilisateur n'a pas été banni depuis sa connexion
        $user = (new User())->findById($_SESSION['user_id']);

        if (!$user || $user['status'] === 'banned') {
            // Détruire la session et rediriger
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }
            session_destroy();
            header('Location: /login');
            exit;
        }
    }
}
