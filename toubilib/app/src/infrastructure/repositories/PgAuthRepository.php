<?php
declare(strict_types=1);

namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;

class PgAuthRepository implements AuthRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findUserByEmail(string $email): ?array
    {
        $sql = 'SELECT id, email, password, role FROM users WHERE email = :email LIMIT 1';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            return [
                'id' => (string)$row['id'],
                'email' => (string)$row['email'],
                'password' => (string)$row['password'],
                'role' => (int)$row['role'],
            ];
        } catch (\Throwable $e) {
            error_log('PgAuthRepository error: ' . $e->getMessage());
            return null;
        }
    }
}