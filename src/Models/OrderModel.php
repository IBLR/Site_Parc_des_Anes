<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

class OrderModel
{
    private PDO $db;
    private string $table = 'orders';


    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


    /**
     * Enregistre une commande et toutes ses lignes, avec ses coordonnées de livraison.
     * La commande et ses lignes sont enregistrées
     * dans une seule transaction.
     * Si une seule étape échoue, aucune donnée
     * n'est conservée en base.
     * Le numéro de commande est généré à partir de l'ID technique
     * après insertion.
     */
    public function createOrder(
        int $userId,
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        string $address,
        string $postcode,
        string $city,
        array $cart
    ): int|false {

        // -------------------------------------------------
        // Début de la transaction
        // -------------------------------------------------
        $this->db->beginTransaction();


        // -------------------------------------------------
        // Création de la commande
        // -------------------------------------------------
        /*
         * order_number doit recevoir une valeur dès l'insertion
         * car la colonne est unique.
         *
         * Cette valeur temporaire sera remplacée immédiatement
         * après récupération de l'ID technique de la commande.
         */
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table}
            (
                order_number,
                user_id,
                last_name,
                first_name,
                email,
                phone,
                address,
                postcode,
                city,
                status
            )
            VALUES
            (
                :order_number,
                :user_id,
                :last_name,
                :first_name,
                :email,
                :phone,
                :address,
                :postcode,
                :city,
                :status
            )"
        );


        $success = $stmt->execute([
            'order_number' => 'TEMP-' . uniqid(),
            'user_id'    => $userId,
            'last_name'  => $lastName,
            'first_name' => $firstName,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
            'postcode'   => $postcode,
            'city'       => $city,
            'status'     => 'pending'
        ]);

        if (!$success) {
            $this->db->rollBack();
            return false;
        }


        // Récupération de l'ID technique de la commande
        $orderId = (int) $this->db->lastInsertId();

        /*
         * Génération du numéro de commande affichable.
         *
         * Exemple :
         * CMD-2026-000017
         */
        $orderNumber = 'CMD-'
            . date('Y')
            . '-'
            . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);


        // Remplacement du numéro temporaire
        $stmt = $this->db->prepare(
            "UPDATE {$this->table}
            SET order_number = :order_number
            WHERE id = :id"
        );

        $success = $stmt->execute([
            'order_number' => $orderNumber,
            'id' => $orderId
        ]);

        if (!$success) {
            $this->db->rollBack();
            return false;
        }


        // -------------------------------------------------
        // Création des lignes de commande
        // -------------------------------------------------

        foreach ($cart as $item) {

            $success = $this->createOrderItem(
                $orderId,
                (int) $item['id'],
                $item['reference'],
                $item['name'],
                (float) $item['price'],
                (int) $item['quantity']
            );


            if (!$success) {
                $this->db->rollBack();
                return false;
            }
        }


        // -------------------------------------------------
        // Validation définitive de la transaction
        // -------------------------------------------------

        $this->db->commit();


        return $orderId;
    }


    /**
     * Enregistre une ligne de commande.
     *
     * product_id correspond à l'ID technique du produit.
     */
    public function createOrderItem(
        int $orderId,
        int $productId,
        string $productReference,
        string $productName,
        float $unitPrice,
        int $quantity
    ): bool {


        $subtotal = $unitPrice * $quantity;


        $stmt = $this->db->prepare(
            "INSERT INTO order_items
            (
                order_id,
                product_id,
                product_reference,
                product_name,
                unit_price,
                quantity,
                subtotal
            )
            VALUES
            (
                :order_id,
                :product_id,
                :product_reference,
                :product_name,
                :unit_price,
                :quantity,
                :subtotal
            )"
        );


        return $stmt->execute([
            'order_id'          => $orderId,
            'product_id'        => $productId,
            'product_reference' => $productReference,
            'product_name'      => $productName,
            'unit_price'        => $unitPrice,
            'quantity'          => $quantity,
            'subtotal'          => $subtotal
        ]);


    }

    
    /**
     * Retrouve le numéro de commande client.
     */
    public function findOrderNumberById(int $orderId): string|false
    {
        $stmt = $this->db->prepare(
            "SELECT order_number
            FROM {$this->table}
            WHERE id = :id"
        );

        $stmt->execute([
            'id' => $orderId
        ]);

        $orderNumber = $stmt->fetchColumn();

        if ($orderNumber === false) {
            return false;
        }

        return (string) $orderNumber;
    }


    /**
     * Retourne l'historique des commandes d'un utilisateur.
     */
    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.created_at,
                o.order_number,
                COALESCE(SUM(oi.subtotal), 0) AS total
            FROM {$this->table} o
            LEFT JOIN order_items oi
                ON oi.order_id = o.id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
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
     * Retourne toutes les commandes pour l'administration.
     */
    public function findAllForAdmin(): array
    {
        $stmt = $this->db->query("
            SELECT
                o.*,
                COALESCE(SUM(oi.subtotal), 0) AS total
            FROM {$this->table} o
            LEFT JOIN order_items oi
                ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Retourne une commande par ID.
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
     * Retourne toutes les lignes d'une commande.
     */
    public function findOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM order_items
            WHERE order_id = :order_id
            ORDER BY id ASC
        ");

        $stmt->execute([
            'order_id' => $orderId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Modifie le statut d'une commande.
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