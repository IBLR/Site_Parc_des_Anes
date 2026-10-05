<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Core\Logger;

use Exception;

/**
 * Handles API requests related to products.
 */
class ApiProductController extends BaseController
{
    /** Handles product database operations. */
    private ProductModel $model;

    /**
     * Initializes the controller and its required model.
     */
    public function __construct()
    {
        $this->model = new ProductModel();
    }

    /**
     * Returns all active products as a JSON response.
     *
     * Product image filenames are converted into their corresponding public URLs
     * before the response is encoded.
     */
    public function index(): void
    {
        try {
            $products = $this->model->findAllActiveItems();

            // Convert stored image filenames into public image URLs.
            foreach ($products as $key => $product) {
                if (!empty($product['image'])) {
                    $products[$key]['image'] =
                        UPLOAD_PRODUCTS_URL . $product['image'];
                }
            }

            header('Content-Type: application/json; charset=utf-8');

            echo json_encode(
                $products,
                JSON_UNESCAPED_UNICODE
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
    
    
    /**
     * Handles exceptions raised during API requests.
     *
     * Logs the exception and returns an appropriate JSON error response.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        http_response_code(500);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'error' => 'Une erreur est survenue.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}