<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController
{
    public static function register()
    {
        // mostra form o processa POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '') ?: null;
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $full_name = trim(trim($first_name . ' ' . $last_name));

            $errors = [];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email non valida.';
            if ($username && strlen($username) < 3) $errors[] = 'Il nome utente deve avere almeno 3 caratteri.';
            if (strlen($password) < 8) $errors[] = 'La password deve essere almeno 8 caratteri.';
            if ($password !== $password_confirm) $errors[] = 'Le password non coincidono.';
            if (User::findByEmail($email)) $errors[] = 'Email già registrata.';
            if ($username && User::findByUsername($username)) $errors[] = 'Username già in uso.';

            if (empty($errors)) {
                $ph = password_hash($password, PASSWORD_DEFAULT);
                $userId = User::create($email, $ph, $full_name, $username);
                $_SESSION['user_id'] = $userId;
                session_regenerate_id(true);
                header('Location: /index.php');
                exit;
            } else {
                include __DIR__ . '/../Views/layout/header.php';
                include __DIR__ . '/../Views/auth/register.php';
                include __DIR__ . '/../Views/layout/footer.php';
            }
        } else {
            include __DIR__ . '/../Views/layout/header.php';
            include __DIR__ . '/../Views/auth/register.php';
            include __DIR__ . '/../Views/layout/footer.php';
        }
    }

    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $errors = [];
            // allow username or email
            $user = null;
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $user = User::findByEmail($login);
            }
            if (!$user) {
                $user = User::findByUsername($login);
            }
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors[] = 'Credenziali non valide.';
            }
            if (empty($errors)) {
                $_SESSION['user_id'] = $user['id'];
                session_regenerate_id(true);
                header('Location: /index.php');
                exit;
            } else {
                include __DIR__ . '/../Views/layout/header.php';
                include __DIR__ . '/../Views/auth/login.php';
                include __DIR__ . '/../Views/layout/footer.php';
            }
        } else {
            include __DIR__ . '/../Views/layout/header.php';
            include __DIR__ . '/../Views/auth/login.php';
            include __DIR__ . '/../Views/layout/footer.php';
        }
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /index.php');
        exit;
    }
}
