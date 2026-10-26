<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Handles database operations for products.
 */
class ProductModel
{
    private PDO $db;
    private string $table = 'products';

    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }

    /**
     * Returns all available products.
     */
    public function findAllActiveItems(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM {$this->table}
            WHERE is_available = :available
            ORDER BY id ASC
        ");

        $stmt->execute([
            'available' => 1
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a product by its ID.
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
     * Returns all products, ordered from newest to oldest.
     */
    public function findAllItems(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM {$this->table}
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a product.
     */
    public function createItem(array $data, ?string $image = null): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
            (
                category_id,
                name,
                description,
                price,
                weight,
                is_available,
                image
            )
            VALUES
            (
                :category_id,
                :name,
                :description,
                :price,
                :weight,
                :is_available,
                :image
            )
        ");

        return $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'weight' => $data['weight'],
            'is_available' => $data['is_available'],
            'image' => $image
        ]);
    }

    /**
     * Updates a product by its ID.
     */
    public function updateItemById(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET
                category_id = :category_id,
                name = :name,
                description = :description,
                price = :price,
                weight = :weight,
                is_available = :is_available,
                image = :image
            WHERE id = :id
        ");

        return $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'weight' => $data['weight'],
            'is_available' => $data['is_available'],
            'image' => $data['image'],
            'id' => $id
        ]);
    }


    /**
     * Counts the products associated with a category.
     */
    public function countItemsByCategoryId(int $categoryId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM {$this->table}
            WHERE category_id = :category_id
        ");

        $stmt->execute([
            'category_id' => $categoryId
        ]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Deletes a product by its ID.
     */
    public function deleteItemById(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table}
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Returns the ID of the last inserted row.
     */
    public function getLastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    // Updates a product's reference (Incrementation)
    public function updateReference(int $id, string $reference): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET reference = :reference
            WHERE id = :id
        ");

        return $stmt->execute([
            'reference' => $reference,
            'id' => $id
        ]);
    }
}