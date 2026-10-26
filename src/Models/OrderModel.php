<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Handles database operations for orders and their items.
 */
class OrderModel
{
    private PDO $db;
    private string $table = 'orders';


    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


    /**
     * Saves an order, all its items, and the customer's delivery details.
     *
     * The order and its items are saved within a single transaction.
     * If any step fails, none of the data is retained in the database.
     * The order number is generated from the internal ID after insertion.
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

        // Start the transaction
        $this->db->beginTransaction();


        // Create the order
        /*
         * order_number must have a value when the row is inserted
         * because the column has a UNIQUE constraint.
         *
         * This temporary value is replaced immediately after
         * retrieving the order's internal ID.
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


        // Retrieve the order's internal ID
        $orderId = (int) $this->db->lastInsertId();

        // Generate the customer-facing order number.
        // Example: CMD-2026-000017
        $orderNumber = 'CMD-'
            . date('Y')
            . '-'
            . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);


        // Replace the temporary order number
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


        // Create the order items
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


        // Commit the transaction
        $this->db->commit();

        return $orderId;
    }


    /**
     * Saves an order item.
     *
     * product_id is the product's internal ID.
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
     * Retrieves the customer-facing order number
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
     * Returns a user's order history
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
     * Returns all orders for the administration dashboard
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
     * Returns an order by its ID
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
     * Returns all items for an order
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
     * Updates the status of an order
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