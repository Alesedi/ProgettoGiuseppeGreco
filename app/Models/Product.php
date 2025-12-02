<?php
require_once __DIR__ . '/Database.php';

class Product
{
    public static function all(?string $q = null)
    {
        $pdo = Database::getConnection();
        if ($q) {
            $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE :q OR description LIKE :q ORDER BY created_at DESC');
            $stmt->execute(['q' => "%$q%"]);
        } else {
            $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
        }
        return $stmt->fetchAll();
    }

    public static function find(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function options(int $productId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM product_options WHERE product_id = :pid');
        $stmt->execute(['pid' => $productId]);
        return $stmt->fetchAll();
    }

    public static function getAll(?int $limit = null)
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT * FROM products ORDER BY created_at DESC';
        if ($limit) {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
