<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

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

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                // In a real app, store this token in DB tied to user
                setcookie('remember_me', $token, time() + (86400 * 30), "/", "", isset($_SERVER['HTTPS']), true);
            }

            header('Location: /dashboard');
            exit;
        }

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

        // Simplistic validation for now
        if (empty($username) || empty($email) || empty($password)) {
            return $this->render('auth/register', [
                'error' => 'Tous les champs sont obligatoires.'
            ], false);
        }

        if ($this->userModel->findByEmail($email) || $this->userModel->findByUsername($username)) {
            return $this->render('auth/register', [
                'error' => "L'email ou le nom d'utilisateur est déjà utilisé."
            ], false);
        }

        $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        ]);

        header('Location: /login');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
