<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles administration actions related to products.
 */
class AdminProductController extends BaseController
{
    /** URL used for product administration redirects. */
    private string $url;

    /** View path used to render the product administration pages. */
    private string $path;

    /** Handles product database operations. */
    private ProductModel $model;

    /** Handles product category database operations. */
    private ProductCategoryModel $productCategoryModel;

    /** Maximum allowed product name length. */
    private const MAX_NAME_LENGTH = 60;

    /** Maximum allowed product description length. */
    private const MAX_DESCRIPTION_LENGTH = 200;

    /** Maximum allowed product weight length. */
    private const MAX_WEIGHT_LENGTH = 10;


    /**
     * Initializes the controller and its required models.
     *
     * Administrator access is required before any product action can be used.
     */
    public function __construct()
    {
        $this->requireAdmin();

        $this->url = 'admin/produits';
        $this->path = 'admin/products';

        $this->model = new ProductModel();
        $this->productCategoryModel = new ProductCategoryModel();
    }

    
    /**
     * Displays the main product administration page.
     */
    public function index()
    {
        try {
            $flash = $this->getFlashData();

            // Retrieve the product selected for editing, if any.
            $editId = null;

            if (isset($_GET['edit'])) {
                $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
            }

            $editItem = null;

            if ($flash['editItemSession']) {
                // Preserve the values entered by the user after a previous validation error.
                $editItem = $flash['editItemSession'];
            } elseif ($editId) {
                $editItem = $this->model->findItemById($editId);
            }

            $viewData = $this->getProductViewData(
                [
                    'editItem' => $editItem,
                    'updateErrors' => $flash['updateErrors'],
                    'success' => $flash['success'],
                    'successProductId' => $flash['successProductId'],
                    'successType' => $flash['successType'],
                ]
            );

            $this->renderViewAndMeta(
                $this->path . '/index',
                array_merge(
                    $viewData,
                    $this->scriptsAndStylesAssets(),
                    [
                        'headerView' => 'header_admin'
                    ]
                )
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Creates a new product from the submitted form data.
     */
    public function createItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            /*
             * CSRF verification
             */
            if (!CsrfProtect::verifyToken('product_create', $_POST['csrf_token'] ?? null)) {
                throw new Exception('Token CSRF invalide.');
            }

            /*
             * Field validation
             */
            $result = $this->validateProductData($_POST);
            $data = $result['data'];
            $errors = $result['errors'];

            /*
             * Image handling
             */
            $resultImage = $this->uploadImage($_FILES['image'] ?? null);

            if (!empty($resultImage['errors'])) {
                $errors = array_merge($errors, $resultImage['errors']);
            }

            /*
             * Return to the form when validation fails.
             */
            if (!empty($errors)) {
                $this->renderViewAndMeta(
                    $this->path . '/index',
                    array_merge(
                        $this->getProductViewData(
                            [
                                'createErrors' => $errors,
                                'old' => $data,
                            ]
                        ),
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_admin'
                        ]
                    )
                );

                return;
            }

            /*
             * Create the product.
             */
            $this->model->createItem($data, $resultImage['image']);

            /*
             * Generate the product reference from its database identifier.
             */
            $id = $this->model->getLastInsertId();

            $reference = 'PROD-' . str_pad($id, 6, '0', STR_PAD_LEFT);

            $this->model->updateReference($id, $reference);

            /*
             * Store the success message before redirecting to the product list.
             */
            $_SESSION['success'] = "Produit créé.";
            $_SESSION['successType'] = "product_create";

            header('Location: ' . BASE_URL . '/?url=' . $this->url);
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Updates an existing product from the submitted form data.
     */
    public function updateItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            /*
             * CSRF verification
             */
            if (!CsrfProtect::verifyToken('product_update', $_POST['csrf_token'] ?? null)) {
                throw new Exception('Token CSRF invalide.');
            }

            /*
             * Retrieve the product ID.
             */
            $id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);

            if (!$id) {
                throw new Exception("ID invalide.");
            }

            /*
             * Load the existing product before applying changes.
             */
            $product = $this->model->findItemById($id);

            if (!$product) {
                throw new Exception("Produit introuvable.");
            }

            /*
             * Validate the submitted fields.
             */
            $result = $this->validateProductData(
                [
                    'category_id' => $_POST['category_id'] ?? '',
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'price' => $_POST['price'] ?? '',
                    'weight' => $_POST['weight'] ?? '',
                    'is_available' => !empty($_POST['is_available']) ? 1 : 0,
                ]
            );

            $data = $result['data'];
            $errors = $result['errors'];

            /*
             * Handle the existing or newly uploaded image.
             */
            $image = $product['image'];

            // Delete the existing image when explicitly requested.
            if (
                !empty($_POST['delete_image']) &&
                $_POST['delete_image'] === '1' &&
                !empty($product['image'])
            ) {
                $this->safeDeleteFile(UPLOAD_PRODUCTS_DIR . $image);
                $image = null;
            } else {
                // Process a potential replacement image.
                $resultImage = $this->uploadImage($_FILES['image'] ?? null);

                if (!empty($resultImage['errors'])) {
                    $errors = array_merge($errors, $resultImage['errors']);
                } elseif ($resultImage['image'] !== null) {
                    if ($image) {
                        $this->safeDeleteFile(UPLOAD_PRODUCTS_DIR . $image);
                    }

                    $image = $resultImage['image'];
                }
            }

            $data['image'] = $image;

            /*
             * Preserve the submitted values when validation fails.
             */
            if (!empty($errors)) {
                $_SESSION['updateErrors'] = $errors;

                /*
                 * Preserve the exact values entered by the user for form redisplay.
                 */
                $_SESSION['editItem'] =
                    $this->buildEditItemFromPost($product, $id, $image);

                header('Location: ' . BASE_URL . '/?url=' . $this->url . '&edit=' . $id . '#product-' . $id);
                exit;
            }

            /*
             * Update the product in the database.
             */
            $this->model->updateItemById($id, $data);

            /*
             * Store the success message before redirecting to the updated product.
             */
            $_SESSION['success'] = "Produit modifié.";
            $_SESSION['successProductId'] = $id;
            $_SESSION['successType'] = "product_update";

            header('Location: ' . BASE_URL . '/?url=' . $this->url . '#product-' . $id);
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Deletes an existing product and its associated image.
     */
    public function deleteItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            /*
             * CSRF verification
             */
            if (!CsrfProtect::verifyToken('product_delete', $_POST['csrf_token'] ?? null)) {
                throw new Exception('Token CSRF invalide.');
            }

            /*
             * Retrieve the product ID.
             */
            $id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);

            if (!$id) {
                throw new Exception("ID invalide.");
            }

            /*
             * Retrieve the product before deletion.
             */
            $product = $this->model->findItemById($id);

            if (!$product) {
                throw new Exception("Produit introuvable.");
            }

            /*
             * Delete the associated image when one exists.
             */
            if (!empty($product['image'])) {
                $this->safeDeleteFile(UPLOAD_PRODUCTS_DIR . $product['image']);
            }

            /*
             * Delete the product from the database.
             */
            $this->model->deleteItemById($id);

            /*
             * Store the success message before redirecting to the product list.
             */
            $_SESSION['success'] = "Produit supprimé.";
            $_SESSION['successType'] = "product_delete";

            header('Location: ' . BASE_URL . '/?url=' . $this->url);
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during product administration.
     *
     * Logs the exception, displays its details in development mode,
     * or stores the error in the session before redirecting to the product page.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] = $e->getMessage();

        header('Location: ' . BASE_URL . '/?url=' . $this->url);
        exit;
    }


    /**
     * Safely deletes an image file located inside the product upload directory.
     *
     * The resolved file path is checked against the allowed base directory
     * before deletion to prevent access to files outside the upload directory.
     */
    private function safeDeleteFile(string $filePath): void
    {
        $file = realpath($filePath);
        $base = realpath(UPLOAD_PRODUCTS_DIR);

        if (!$file || !$base) {
            return;
        }

        if (!str_starts_with($file, $base)) {
            return;
        }

        if (is_file($file) && is_writable($file)) {
            unlink($file);
        }
    }


    /**
     * Generates the CSRF tokens required by the product administration forms.
     */
    private function getCsrfTokens(): array
    {
        return [
            'csrfProductCreate' => CsrfProtect::generateToken('product_create'),
            'csrfProductUpdate' => CsrfProtect::generateToken('product_update'),
            'csrfProductDelete' => CsrfProtect::generateToken('product_delete'),
        ];
    }


    /**
     * Validates and normalizes the data shared by product creation and update.
     */
    private function validateProductData(array $data): array
    {
        $errors = [];

        // Normalize the values received from the form.
        $data['category_id'] = trim($data['category_id'] ?? '');
        $data['name'] = trim($data['name'] ?? '');
        $data['description'] = trim($data['description'] ?? '');
        $data['price'] = trim((string)($data['price'] ?? ''));
        $data['weight'] = trim((string)($data['weight'] ?? ''));

        /*
         * Category
         */
        $data['category_id'] = filter_var($data['category_id'], FILTER_VALIDATE_INT);

        if (!$data['category_id']) {
            $errors['category_id'] = 'Catégorie non sélectionnée.';
        } elseif (!$this->productCategoryModel->findItemById($data['category_id'])) {
            $errors['category_id'] = 'Catégorie invalide.';
        }

        /*
         * Title
         */
        if ($data['name'] === '') {
            $errors['name'] = 'Titre obligatoire.';
        } elseif ($data['name'] !== strip_tags($data['name'])) {
            $errors['name'] = 'HTML interdit.';
        } elseif (mb_strlen($data['name']) > self::MAX_NAME_LENGTH) {
            $errors['name'] = sprintf(
                'Titre trop long (max. %d caractères).',
                self::MAX_NAME_LENGTH
            );
        }

        /*
         * Description
         */
        if ($data['description'] === '') {
            $errors['description'] = 'Description obligatoire.';
        } elseif ($data['description'] !== strip_tags($data['description'])) {
            $errors['description'] = 'HTML interdit.';
        } elseif (mb_strlen($data['description']) > self::MAX_DESCRIPTION_LENGTH) {
            $errors['description'] = sprintf(
                'Description trop longue (max. %d caractères).',
                self::MAX_DESCRIPTION_LENGTH
            );
        }

        /*
         * Price
         */
        if ($data['price'] === '') {
            $errors['price'] = 'Prix obligatoire.';
        } elseif (!is_numeric($data['price'])) {
            $errors['price'] = 'Prix invalide.';
        } elseif ((float)$data['price'] < 0) {
            $errors['price'] = 'Prix négatif interdit.';
        } else {
            $data['price'] = (float)$data['price'];
        }

        /*
         * Weight
         */
        if ($data['weight'] !== '') {
            if ($data['weight'] !== strip_tags($data['weight'])) {
                $errors['weight'] = 'HTML interdit.';
            } elseif (mb_strlen($data['weight']) > self::MAX_WEIGHT_LENGTH) {
                $errors['weight'] = sprintf(
                    'Poids trop long (max. %d caractères).',
                    self::MAX_WEIGHT_LENGTH
                );
            }
        } else {
            $data['weight'] = null;
        }

        /*
         * Availability
         */
        $data['is_available'] = !empty($data['is_available']) ? 1 : 0;

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }


    /**
     * Handles the complete product image upload process.
     */
    private function uploadImage(?array $file): array
    {
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return [
                'image' => null,
                'errors' => []
            ];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Erreur lors de l'upload de l'image."
                ]
            ];
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Image trop volumineuse (2 Mo maximum)."
                ]
            ];
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Upload invalide."
                ]
            ];
        }

        $imageInfo = getimagesize($file['tmp_name']);

        if ($imageInfo === false) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Le fichier n'est pas une image valide."
                ]
            ];
        }

        /** MIME types accepted for product images. */
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        /** File extensions accepted for product images. */
        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        $mimeType = mime_content_type($file['tmp_name']);
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Format d'image non autorisé."
                ]
            ];
        }

        if (!in_array($extension, $allowedExtensions, true)) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Extension d'image non autorisée."
                ]
            ];
        }

        if (!is_dir(UPLOAD_PRODUCTS_DIR)) {
            mkdir(UPLOAD_PRODUCTS_DIR, 0755, true);
        }

        $imageName = uniqid('', true) . '.' . $extension;

        if (!move_uploaded_file(
            $file['tmp_name'],
            UPLOAD_PRODUCTS_DIR . $imageName
        )) {
            return [
                'image' => null,
                'errors' => [
                    'image' => "Impossible d'enregistrer l'image."
                ]
            ];
        }

        return [
            'image' => $imageName,
            'errors' => []
        ];
    }


    /**
     * Prepares the common data required by the product administration views.
     */
    private function getProductViewData(array $data = []): array
    {
        return array_merge(
            [
                'items' => $this->model->findAllItems(),
                'editItem' => null,
                'categories' => $this->productCategoryModel->findAllItems(),
            ],
            $this->getCsrfTokens(),
            $data
        );
    }


    /**
     * Retrieves and clears flash messages and validation data stored in the session.
     */
    private function getFlashData(): array
    {
        $data = [
            'success' => $_SESSION['success'] ?? null,
            'successProductId' => $_SESSION['successProductId'] ?? null,
            'successType' => $_SESSION['successType'] ?? null,
            'updateErrors' => $_SESSION['updateErrors'] ?? [],
            'editItemSession' => $_SESSION['editItem'] ?? null,
        ];

        unset(
            $_SESSION['success'],
            $_SESSION['successProductId'],
            $_SESSION['successType'],
            $_SESSION['updateErrors'],
            $_SESSION['editItem']
        );

        return $data;
    }


    /**
     * Rebuilds the product data from submitted values after an update validation error.
     */
    private function buildEditItemFromPost(
        array $product,
        int $id,
        ?string $image
    ): array {
        return array_merge(
            $product,
            [
                'id' => $id,
                'category_id' => $_POST['category_id'] ?? '',
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? '',
                'weight' => $_POST['weight'] ?? '',
                'is_available' => !empty($_POST['is_available']) ? 1 : 0,
                'image' => $image,
            ]
        );
    }


    /**
     * Returns the assetss required by the product administration pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
                'admin-products.css',
            ],

            'internalScripts' => [
                'admin_products.js',
            ],
        ];
    }
}