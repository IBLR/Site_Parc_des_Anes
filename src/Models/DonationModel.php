<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Handles database operations for donations.
 */
class DonationModel
{
    private PDO $db;
    private string $table = 'donations';


    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


    /**
     * Saves a donation.
     *
     * The donation number is generated from the
     * internal ID after the donation has been inserted.
     */
    public function createDonation(
        int $userId,
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        float $amount,
        string $comment
    ): int|false {


        // Start the transaction
        $this->db->beginTransaction();

        // Create the donation
        /*
         * donation_number must have a value when the row is
         * inserted because the column is UNIQUE and NOT NULL.
         *
         * This temporary value is immediately replaced after
         * retrieving the donation's internal ID.
         */
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table}
            (
                user_id,
                donation_number,
                last_name,
                first_name,
                email,
                phone,
                amount,
                comment,
                status
            )
            VALUES
            (
                :user_id,
                :donation_number,
                :last_name,
                :first_name,
                :email,
                :phone,
                :amount,
                :comment,
                :status
            )"
        );

        $success = $stmt->execute([
            'user_id'         => $userId,
            'donation_number' => 'TEMP-' . uniqid(),
            'last_name'       => $lastName,
            'first_name'      => $firstName,
            'email'           => $email,
            'phone'           => $phone !== '' ? $phone : null,
            'amount'          => $amount,
            'comment'         => $comment !== '' ? $comment : null,
            'status'          => 'pending'
        ]);

        if (!$success) {
            $this->db->rollBack();
            return false;
        }

        // Retrieve the donation's internal ID
        $donationId = (int) $this->db->lastInsertId();


        // Generate the displayable donation number
        // Example : DON-2026-000001
        $donationNumber = 'DON-'
            . date('Y')
            . '-'
            . str_pad((string) $donationId, 6, '0', STR_PAD_LEFT);


        // Replace the temporary donation number
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
            SET donation_number = :donation_number
            WHERE id = :id"
        );

        $success = $stmt->execute([
            'donation_number' => $donationNumber,
            'id'              => $donationId
        ]);

        if (!$success) {
            $this->db->rollBack();
            return false;
        }

        // Commit the transaction
        $this->db->commit();

        return $donationId;
    }


    /**
     * Returns a user's donation history
     */
    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                created_at,
                amount
            FROM {$this->table}
            WHERE user_id = :user_id
            ORDER BY created_at DESC
        ");

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * ADMINISTRATION DASHBOARD
     */

    /**
     * Returns all donations for the administration dashboard
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
     * Returns a donation by its ID
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
     * Updates the processing status of a donation
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
            'id'     => $id
        ]);
    }
}