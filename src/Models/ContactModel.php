<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Handles database operations for contact messages.
 */
class ContactModel
{
    private PDO $db;
    private string $table = 'contact_messages';

    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }

    // Check for duplicate messages
    public function exists(string $email, string $message): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id 
             FROM {$this->table}
             WHERE email = :email
             AND message = :message
             LIMIT 1"
        );

        $stmt->execute([
            'email' => $email,
            'message' => $message
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Save a contact form submission
    public function saveContactForm(
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        string $message
    ): bool {

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table}
            (
                last_name,
                first_name,
                email,
                phone,
                message,
                created_at
            )
            VALUES
            (
                :last_name,
                :first_name,
                :email,
                :phone,
                :message,
                NOW()
            )"
        );

        return $stmt->execute([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'phone' => $phone,
            'message' => $message
        ]);
    }


    /**
     * ADMINISTRATION DASHBOARD
     */

    /**
     * Returns all contact messages for the administration dashboard.
     */
    public function findAllForAdmin(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM {$this->table}
            ORDER BY created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Returns a contact message by its ID.
     */
    public function findItemById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM {$this->table}
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Updates the processing status of a contact message.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }
}