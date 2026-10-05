<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

class DonationModel
{
    private PDO $db;
    private string $table = 'donations';


    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


    /**
     * Enregistre un don.
     *
     * Le numéro de don est généré à partir
     * de l'ID technique après insertion.
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

        // -------------------------------------------------
        // Début de la transaction
        // -------------------------------------------------

        $this->db->beginTransaction();


        // -------------------------------------------------
        // Création du don
        // -------------------------------------------------

        /*
         * donation_number doit recevoir une valeur dès
         * l'insertion car la colonne est UNIQUE et NOT NULL.
         *
         * Cette valeur temporaire sera remplacée immédiatement
         * après récupération de l'ID technique du don.
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


        // -------------------------------------------------
        // Récupération de l'ID technique du don
        // -------------------------------------------------

        $donationId = (int) $this->db->lastInsertId();


        // -------------------------------------------------
        // Génération du numéro de don affichable
        // -------------------------------------------------

        /*
         * Exemple :
         * DON-2026-000001
         */
        $donationNumber = 'DON-'
            . date('Y')
            . '-'
            . str_pad((string) $donationId, 6, '0', STR_PAD_LEFT);


        // -------------------------------------------------
        // Remplacement du numéro temporaire
        // -------------------------------------------------

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


        // -------------------------------------------------
        // Validation définitive de la transaction
        // -------------------------------------------------

        $this->db->commit();


        return $donationId;
    }


    /**
     * Retourne l'historique des dons d'un utilisateur.
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
     * Retourne tous les dons pour l'administration.
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
     * Retourne un don par ID.
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
     * Modifie le statut de traitement d'un don.
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