<?php

namespace App\Models;

use App\Core\DatabaseConnect;
use PDO;

/**
 * Handles database operations for product categories.
 */
class ProductCategoryModel
{
    private PDO $db;
    private string $table = 'products_categories';


    public function __construct()
    {
        $this->db = DatabaseConnect::getInstance()->getConnection();
    }


    /**
     * Returns all categories
     */
    public function findAllItems(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                label,
                description,
                display_order
            FROM {$this->table}
            ORDER BY display_order ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Returns a category by its ID
     */
    public function findItemById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                label,
                description,
                display_order
            FROM {$this->table}
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Finds a category by its label
     */
    public function findItemByLabel(string $label): array|false
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                label,
                description,
                display_order
            FROM {$this->table}
            WHERE label = :label
        ");

        $stmt->execute([
            'label' => $label
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Finds a category by its display order
     */
    public function findItemByDisplayOrder(int $displayOrder): array|false
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                label,
                description,
                display_order
            FROM {$this->table}
            WHERE display_order = :display_order
        ");

        $stmt->execute([
            'display_order' => $displayOrder
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Creates a category
     */
    public function createItem(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
            (
                label,
                description,
                display_order
            )
            VALUES
            (
                :label,
                :description,
                :display_order
            )
        ");

        return $stmt->execute([
            'label' => $data['label'],
            'description' => $data['description'],
            'display_order' => $data['display_order']
        ]);
    }


    /**
     * Updates a category by its ID
     */
    public function updateItemById(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET
                label = :label,
                description = :description,
                display_order = :display_order
            WHERE id = :id
        ");

        return $stmt->execute([
            'label' => $data['label'],
            'description' => $data['description'],
            'display_order' => $data['display_order'],
            'id' => $id
        ]);
    }


    /**
     * Deletes a category by its ID
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
     * Returns the ID of the last inserted row
     */
    public function getLastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}