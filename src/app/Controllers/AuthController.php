<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\MailHelper;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginView()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        return $this->render('auth/login', ['title' => 'Connexion — GameVault'], false);
    }

    public function registerView()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        return $this->render('auth/register', ['title' => 'Inscription — GameVault'], false);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Brute-force protection
        $redis = \App\Helpers\RedisHelper::getInstance();
        $attemptsKey = "login_attempts:" . md5($email);
        $attempts = $redis->getCache($attemptsKey) ?? 0;

        if ($attempts >= 5) {
            return $this->render('auth/login', [
                'title' => 'Connexion — GameVault',
                'error' => 'Trop de tentatives. Veuillez réessayer dans 15 minutes.'
            ], false);
        }

        $user = $this->userModel->findByEmail($email);

        // Multi-layered login verification
        if ($user && $user['status'] === 'banned') {
            return $this->render('auth/login', [
                'title' => 'Connexion — GameVault',
                'error' => 'Votre compte a été banni par un administrateur.'
            ], false);
        }

        if ($user && password_verify($password, $user['password_hash'])) {
            // Check email verification
            if (empty($user['email_verified_at'])) {
                return $this->render('auth/login', [
                    'title' => 'Connexion — GameVault',
                    'error' => 'Veuillez vérifier votre adresse email avant de vous connecter.',
                    'showResendLink' => true,
                    'resendEmail' => $email
                ], false);
            }

            // Reset attempts on success
            $redis->deleteCache($attemptsKey);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            session_regenerate_id(true);

            header('Location: /dashboard');
            exit;
        }

        // Increment attempts on failure
        $redis->setCache($attemptsKey, $attempts + 1, 900); // 15 min lockout

        return $this->render('auth/login', [
            'title' => 'Connexion — GameVault',
            'error' => 'Identifiants invalides.'
        ], false);
    }

    public function register()
    {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            return $this->render('auth/register', [
                'error' => 'Tous les champs sont obligatoires.'
            ], false);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('auth/register', [
                'error' => 'Adresse email invalide.'
            ], false);
        }

        if (strlen($password) < 8) {
            return $this->render('auth/register', [
                'error' => 'Le mot de passe doit contenir au moins 8 caractères.'
            ], false);
        }

        if (!isset($_POST['terms']) || $_POST['terms'] !== 'on') {
            return $this->render('auth/register', [
                'error' => 'Vous devez accepter les conditions d\'utilisation et la politique de confidentialité.'
            ], false);
        }

        if ($this->userModel->findByEmail($email) || $this->userModel->findByUsername($username)) {
            return $this->render('auth/register', [
                'error' => "L'email ou le nom d'utilisateur est déjà utilisé."
            ], false);
        }

        $token = bin2hex(random_bytes(32));

        $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'user',
            'consent_at' => date('Y-m-d H:i:s'),
            'email_token' => $token
        ]);

        // Send verification email
        $verifyUrl = ($_ENV['APP_URL'] ?? 'http://localhost:8083') . '/verify-email?token=' . $token;
        $emailBody = $this->renderEmail('emails/verify', [
            'username' => $username,
            'verifyUrl' => $verifyUrl
        ]);
        MailHelper::send($email, 'GameVault — Vérifiez votre email', $emailBody);

        return $this->render('auth/verify', [
            'title' => 'Vérification — GameVault',
            'info' => 'Un email de vérification a été envoyé à ' . $email . '. Vérifiez votre boîte de réception.'
        ], false);
    }

    public function verifyEmail()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            return $this->render('auth/verify', [
                'title' => 'Vérification — GameVault',
                'error' => 'Token de vérification manquant.'
            ], false);
        }

        $user = $this->userModel->findByEmailToken($token);

        if (!$user) {
            return $this->render('auth/verify', [
                'title' => 'Vérification — GameVault',
                'error' => 'Token invalide ou déjà utilisé.'
            ], false);
        }

        $this->userModel->verifyEmail($user['id']);

        return $this->render('auth/verify', [
            'title' => 'Vérification — GameVault',
            'success' => 'Votre adresse email a été vérifiée avec succès ! Vous pouvez maintenant vous connecter.'
        ], false);
    }

    public function resendVerification()
    {
        $email = $_GET['email'] ?? '';
        $user = $this->userModel->findByEmail($email);

        if ($user && empty($user['email_verified_at'])) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->updateEmailToken($user['id'], $token);

            $verifyUrl = ($_ENV['APP_URL'] ?? 'http://localhost:8083') . '/verify-email?token=' . $token;
            $emailBody = $this->renderEmail('emails/verify', [
                'username' => $user['username'],
                'verifyUrl' => $verifyUrl
            ]);
            MailHelper::send($email, 'GameVault — Vérifiez votre email', $emailBody);
        }

        return $this->render('auth/verify', [
            'title' => 'Vérification — GameVault',
            'info' => 'Si un compte existe avec cet email, un nouveau lien de vérification a été envoyé.'
        ], false);
    }

    private function renderEmail(string $template, array $data = []): string
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../Views/' . $template . '.php';
        return ob_get_clean();
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        setcookie('remember_me', '', time() - 42000, '/');
        session_destroy();
        header('Location: /login');
        exit;
    }
}
