<?php

namespace App\Controllers\Shop;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use App\Core\Logger;

use Exception;

/**
 * Handles product-related pages in the shop.
 */
class ProductController extends BaseController
{
    /** URL parameter used to access the shop. */
    private string $url;

    /** View path used by the controller. */
    private string $path;

    /** Product model used to access product data. */
    private ProductModel $model;

    /** Product category model used to access category data. */
    private ProductCategoryModel $categoryModel;


    /**
     * Initializes the controller configuration and models.
     */
    public function __construct()
    {
        $this->url = 'boutique';
        $this->path = 'shop';
        $this->model = new ProductModel();
        $this->categoryModel = new ProductCategoryModel();
    }


    /**
     * Displays the main shop page with its product categories.
     */
    public function index()
    {
        try {
            $data = $this->scriptsAndStylesAssets();

            $data['categories'] =
                $this->categoryModel->findAllItems();

            return $this->renderViewAndMeta(
                "{$this->path}" . '/index',
                $data
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Handles exceptions raised during shop operations.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] = $e->getMessage();

        header(
            'Location: ' .
            BASE_URL .
            '/?url=' . $this->url
        );

        exit;
    }

    
    /**
     * Returns the assetss required by the shop pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'shop.css',
            ],

            'internalScripts' => [
                'shop.js',
                'cart_products.js',
            ],
        ];
    }
}