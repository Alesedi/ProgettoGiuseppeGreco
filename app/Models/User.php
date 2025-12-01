<?php
require_once __DIR__ . '/Database.php';

class User
{
    public static function findByEmail(string $email)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public static function findByUsername(string $username)
    {
        $pdo = Database::getConnection();
        // Verifica se la colonna `username` esiste nello schema corrente.
        try {
            $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'username'");
            $check->execute();
            $exists = (int) $check->fetchColumn() > 0;
        } catch (Exception $e) {
            // In caso di errori nell'accesso a information_schema, non provare la query per username.
            $exists = false;
        }

        if ($exists) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
            $stmt->execute(['username' => $username]);
            return $stmt->fetch();
        }

        // Fallback: se non c'è la colonna `username`, prova a trovare l'utente per email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $username]);
        return $stmt->fetch();
    }

    public static function create(string $email, string $passwordHash, ?string $fullName = null, ?string $username = null)
    {
        $pdo = Database::getConnection();
        // Controlla se la colonna `username` esiste nello schema corrente.
        $exists = false;
        try {
            $check = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'username'");
            $check->execute();
            $exists = (int) $check->fetchColumn() > 0;
        } catch (Exception $e) {
            $exists = false;
        }

        if ($username !== null && $exists) {
            $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, full_name, username) VALUES (:email, :ph, :name, :username)');
            $stmt->execute(['email' => $email, 'ph' => $passwordHash, 'name' => $fullName, 'username' => $username]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, full_name) VALUES (:email, :ph, :name)');
            $stmt->execute(['email' => $email, 'ph' => $passwordHash, 'name' => $fullName]);
        }
        return $pdo->lastInsertId();
    }

    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
