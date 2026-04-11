<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\RedisHelper;

class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $user = $this->userModel->findById($_SESSION['user_id']);

        return $this->render('profile/index', [
            'title' => 'Mon Profil — GameVault',
            'user' => $user,
        ]);
    }

    public function update()
    {
        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';

        $user = $this->userModel->findById($userId);

        // Require current password confirmation
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Mot de passe actuel incorrect.',
            ]);
        }

        if (empty($username) || empty($email)) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Le nom d\'utilisateur et l\'email sont obligatoires.',
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Adresse email invalide.',
            ]);
        }

        // Check email uniqueness (exclude current user)
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Cette adresse email est déjà utilisée.',
            ]);
        }

        // Check username uniqueness (exclude current user)
        $existingUser = $this->userModel->findByUsername($username);
        if ($existingUser && $existingUser['id'] != $userId) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Ce nom d\'utilisateur est déjà utilisé.',
            ]);
        }

        $this->userModel->updateProfile($userId, [
            'username' => $username,
            'email' => $email,
        ]);

        // Update session data
        $_SESSION['user_name'] = $username;

        header('Location: /profile?success=1');
        exit;
    }

    public function delete()
    {
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';

        $user = $this->userModel->findById($userId);

        if (!password_verify($currentPassword, $user['password_hash'])) {
            return $this->render('profile/index', [
                'title' => 'Mon Profil — GameVault',
                'user' => $user,
                'error' => 'Mot de passe incorrect. Suppression annulée.',
            ]);
        }

        // Clean up Redis data before SQL deletion
        $redis = RedisHelper::getInstance();
        $redis->deleteUserData($userId);

        // Delete the account (DB cascades handle related records)
        $this->userModel->deleteAccount($userId);

        // Destroy session
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        header('Location: /');
        exit;
    }

    public function export()
    {
        $userId = $_SESSION['user_id'];
        $data = $this->userModel->exportData($userId);

        // Add Redis activity data
        $redis = RedisHelper::getInstance();
        $data['activity_log'] = $redis->getActivity($userId);

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="gamevault_mes_donnees_' . $userId . '.json"');
        header('Content-Length: ' . strlen($json));
        echo $json;
        exit;
    }
}
