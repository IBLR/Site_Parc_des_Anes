<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

class UserModel
{
    private PDO $db;
    private string $table = 'users';

    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


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