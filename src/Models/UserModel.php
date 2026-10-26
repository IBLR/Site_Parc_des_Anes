<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Provides database operations for user accounts.
 */
class UserModel
{
    private PDO $db;
    private string $table = 'users';

    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }

    /**
     * Finds a user by email address.
     */
    public function findUserByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * 
             FROM {$this->table}
             WHERE email = :email"
        );

        $stmt->execute([
            'email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a user account.
     */
    public function createUser(string $email, string $passwordHash): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (email, password)
             VALUES (:email, :password)"
        );

        return $stmt->execute([
            'email' => $email,
            'password' => $passwordHash
        ]);
    }

    /**
     * Finds a user by ID.
     */
    public function findUserById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a user's password.
     */
    public function updateUserPassword(int $id, string $passwordHash): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
             SET password = :password
             WHERE id = :id"
        );

        return $stmt->execute([
            'password' => $passwordHash,
            'id' => $id
        ]);
    }
}