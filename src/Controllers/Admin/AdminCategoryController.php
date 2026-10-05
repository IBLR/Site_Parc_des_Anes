<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles administration actions related to product categories.
 *
 * Provides category listing, creation, modification, and deletion,
 * with authentication, CSRF protection, validation, and error handling.
 */
class AdminCategoryController extends BaseController
{
    /** URL used for category administration redirects. */
    private string $url;

    /** View path used to render the category administration page. */
    private string $path;

    /** Handles product category database operations. */
    private ProductCategoryModel $model;

    /** Handles product database operations related to category dependencies. */
    private ProductModel $productModel;

    /** Maximum allowed length for a category label. */
    private const MAX_LABEL_LENGTH = 60;

    /** Maximum allowed length for a category description. */
    private const MAX_DESCRIPTION_LENGTH = 300;


    /**
     * Initializes the controller and its required models.
     * Administrator access is required before any category action can be used.
     */
    public function __construct()
    {
        $this->requireAdmin();

        $this->url = 'admin/categories';
        $this->path = 'admin/categories';

        $this->model = new ProductCategoryModel();
        $this->productModel = new ProductModel();
    }


    /**
     * Displays the main category administration page.
     */
    public function index()
    {
        try {
            $flash = $this->getFlashData();

            $editId = null;

            if (isset($_GET['edit'])) {
                $editId =
                    filter_var(
                        $_GET['edit'],
                        FILTER_VALIDATE_INT
                    );
            }

            $editItem = null;

            if ($flash['editItemSession']) {
                $editItem =
                    $flash['editItemSession'];
            } elseif ($editId) {
                $editItem =
                    $this->model->findItemById(
                        $editId
                    );
            }

            $this->renderViewAndMeta(
                $this->path . '/index',
                array_merge(
                    $this->getCategoryViewData(
                        [
                            'editItem' => $editItem,

                            'createErrors' =>
                                $flash['createErrors'],

                            'updateErrors' =>
                                $flash['updateErrors'],

                            'old' =>
                                $flash['old'],

                            'success' =>
                                $flash['success'],

                            'successCategoryId' =>
                                $flash['successCategoryId'],

                            'successType' =>
                                $flash['successType'],

                            'deleteError' =>
                                $flash['deleteError'],
                        ]
                    ),
                    $this->scriptsAndStylesAssets(),
                    [
                        'headerView' => 'header_admin'
                    ],
                )
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Creates a new product category from the submitted form.
     */
    public function createItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            // Verify the CSRF token before processing submitted data.
            if (
                !CsrfProtect::verifyToken(
                    'category_create',
                    $_POST['csrf_token'] ?? null
                )
            ) {
                throw new Exception(
                    'Token CSRF invalide.'
                );
            }

            $result =
                $this->validateCategoryData(
                    $_POST
                );

            $data =
                $result['data'];

            $errors =
                $result['errors'];

            // Preserve validation errors and submitted values before redirecting.
            if (!empty($errors)) {
                $_SESSION['createErrors'] =
                    $errors;

                $_SESSION['old'] =
                    $data;

                header(
                    'Location: ' .
                    BASE_URL .
                    '?url=' .
                    $this->url
                );

                exit;
            }

            $this->model->createItem($data);

            $_SESSION['success'] =
                'Catégorie créée.';

            $_SESSION['successType'] =
                'category_create';

            header(
                'Location: ' .
                BASE_URL .
                '?url=' .
                $this->url
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Updates an existing product category from the submitted form.
     */
    public function updateItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            // Verify the CSRF token before processing submitted data.
            if (
                !CsrfProtect::verifyToken(
                    'category_update',
                    $_POST['csrf_token'] ?? null
                )
            ) {
                throw new Exception(
                    'Token CSRF invalide.'
                );
            }

            $id =
                filter_var(
                    $_POST['id'] ?? null,
                    FILTER_VALIDATE_INT
                );

            if (!$id) {
                throw new Exception(
                    'ID invalide.'
                );
            }

            $item =
                $this->model->findItemById($id);

            if (!$item) {
                throw new Exception(
                    'Catégorie introuvable.'
                );
            }

            $result =
                $this->validateCategoryData(
                    array_merge(
                        $_POST,
                        [
                            'id' => $id
                        ]
                    )
                );

            $data =
                $result['data'];

            $errors =
                $result['errors'];

            // Preserve validation errors and submitted values before redirecting.
            if (!empty($errors)) {
                $_SESSION['updateErrors'] =
                    $errors;

                $_SESSION['editItem'] =
                    $this->buildEditItemFromPost(
                        $id
                    );

                header(
                    'Location: ' .
                    BASE_URL .
                    '?url=' .
                    $this->url .
                    '&edit=' .
                    $id .
                    '#category-' .
                    $id
                );

                exit;
            }

            $this->model->updateItemById(
                $id,
                $data
            );

            $_SESSION['success'] =
                'Catégorie modifiée.';

            $_SESSION['successCategoryId'] =
                $id;

            $_SESSION['successType'] =
                'category_update';

            header(
                'Location: ' .
                BASE_URL .
                '?url=' .
                $this->url .
                '#category-' .
                $id
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Deletes an existing product category from the submitted form.
     *
     * Prevents deletion when products are still associated with the category.
     */
    public function deleteItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        try {
            /*
             * Verify the CSRF token.
             */
            if (
                !CsrfProtect::verifyToken(
                    'category_delete',
                    $_POST['csrf_token'] ?? null
                )
            ) {
                throw new Exception(
                    'Token CSRF invalide.'
                );
            }

            /*
             * Retrieve and validate the category ID.
             */
            $id =
                filter_var(
                    $_POST['id'] ?? null,
                    FILTER_VALIDATE_INT
                );

            if (!$id) {
                throw new Exception(
                    'ID invalide.'
                );
            }

            /*
             * Verify that the category exists.
             */
            $item =
                $this->model->findItemById($id);

            if (!$item) {
                throw new Exception(
                    'Catégorie introuvable.'
                );
            }

            /*
             * Check whether products are still associated with the category.
             */
            $productCount =
                $this->productModel
                    ->countItemsByCategoryId($id);

            if ($productCount > 0) {
                $_SESSION['deleteError'] =
                    'Impossible de supprimer cette catégorie : un ou plusieurs produits y sont encore associés.';

                header(
                    'Location: ' .
                    BASE_URL .
                    '?url=' .
                    $this->url
                );

                exit;
            }

            /*
             * Delete the category from the database.
             */
            $this->model->deleteItemById($id);

            /*
             * Store the success message for the next request.
             */
            $_SESSION['success'] =
                'Catégorie supprimée.';

            $_SESSION['successType'] =
                'category_delete';

            header(
                'Location: ' .
                BASE_URL .
                '?url=' .
                $this->url
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during category administration.
     * Logs the exception, displays its details in development mode,
     * or stores a generic session error before redirecting to the category page.
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
            '?url=' .
            $this->url
        );

        exit;
    }


    /**
     * Generates the CSRF tokens required by category forms.
     */
    private function getCsrfTokens(): array
    {
        return [
            'csrfCategoryCreate' => CsrfProtect::generateToken('category_create'),
            'csrfCategoryUpdate' => CsrfProtect::generateToken('category_update'),
            'csrfCategoryDelete' => CsrfProtect::generateToken('category_delete'),
        ];
    }


    /**
     * Validates and normalizes category data for creation or modification.
     *
     * Checks required fields, allowed content, length limits,
     * and uniqueness constraints.
     */
    private function validateCategoryData(array $data): array
    {
        $errors = [];

        $data['label'] = trim($data['label'] ?? '');

        $data['description'] = trim(
            $data['description'] ?? ''
        );

        $data['display_order'] =
            trim(
                $data['display_order'] ?? ''
            );

        /*
         * Validate category label.
         */
        if ($data['label'] === '') {
            $errors['label'] = 'Nom obligatoire.';
        } elseif (
            $data['label'] !== strip_tags($data['label'])
        ) {
            $errors['label'] = 'HTML interdit.';
        } elseif (
            mb_strlen($data['label']) >
            self::MAX_LABEL_LENGTH
        ) {
            $errors['label'] = sprintf(
                'Nom trop long (max. %d caractères).',
                self::MAX_LABEL_LENGTH
            );
        }

        /*
         * Validate category description.
         */
        if ($data['description'] === '') {
            $errors['description'] =
                'Description obligatoire.';
        } elseif (
            $data['description'] !== strip_tags($data['description'])
        ) {
            $errors['description'] =
                'HTML interdit.';
        } elseif (
            mb_strlen($data['description']) >
            self::MAX_DESCRIPTION_LENGTH
        ) {
            $errors['description'] = sprintf(
                'Description trop longue (max. %d caractères).',
                self::MAX_DESCRIPTION_LENGTH
            );
        }

        /*
         * Validate display order.
         */
        $data['display_order'] =
            filter_var(
                $data['display_order'],
                FILTER_VALIDATE_INT
            );

        if (!$data['display_order']) {
            $errors['display_order'] =
                'Ordre d’affichage invalide.';
        } elseif ($data['display_order'] < 1) {
            $errors['display_order'] =
                'L’ordre doit être supérieur à zéro.';
        }

        /*
         * Check label uniqueness.
         */
        if (!empty($data['label'])) {
            $existing =
                $this->model->findItemByLabel(
                    $data['label']
                );

            if (
                $existing &&
                (
                    empty($data['id']) ||
                    (int)$existing['id'] !== (int)$data['id']
                )
            ) {
                $errors['label'] =
                    'Cette catégorie existe déjà.';
            }
        }

        /*
         * Check display order uniqueness.
         */
        if (
            empty($errors['display_order']) &&
            $data['display_order']
        ) {
            $existing =
                $this->model->findItemByDisplayOrder(
                    $data['display_order']
                );

            if (
                $existing &&
                (
                    empty($data['id']) ||
                    (int)$existing['id'] !== (int)$data['id']
                )
            ) {
                $errors['display_order'] =
                    'Cet ordre d’affichage est déjà utilisé.';
            }
        }

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }


    /**
     * Prepares the data required by the category administration view.
     */
    private function getCategoryViewData(array $data = []): array
    {
        return array_merge(
            [
                'items' => $this->model->findAllItems(),
                'editItem' => null,
            ],
            $this->getCsrfTokens(),
            $data
        );
    }


    /**
     * Retrieves and clears flash messages stored in the session.
     */
    private function getFlashData(): array
    {
        $data = [
            'success' => $_SESSION['success'] ?? null,
            'successCategoryId' => $_SESSION['successCategoryId'] ?? null,
            'successType' => $_SESSION['successType'] ?? null,

            'createErrors' => $_SESSION['createErrors'] ?? [],
            'updateErrors' => $_SESSION['updateErrors'] ?? [],

            'deleteError' => $_SESSION['deleteError'] ?? null,

            'old' => $_SESSION['old'] ?? [],

            'editItemSession' =>
                $_SESSION['editItem'] ?? null,
        ];

        unset(
            $_SESSION['success'],
            $_SESSION['successCategoryId'],
            $_SESSION['successType'],

            $_SESSION['createErrors'],
            $_SESSION['updateErrors'],

            $_SESSION['deleteError'],

            $_SESSION['old'],

            $_SESSION['editItem']
        );

        return $data;
    }


    /**
     * Rebuilds a category from submitted data after an update validation error.
     */
    private function buildEditItemFromPost(
        int $id
    ): array {
        return [
            'id' => $id,

            'label' =>
                $_POST['label'] ?? '',

            'description' =>
                $_POST['description'] ?? '',

            'display_order' =>
                $_POST['display_order'] ?? '',
        ];
    }

    
    /**
     * Returns the assetss required by the category administration page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
                'admin-categories.css',
            ],

            'internalScripts' => [
                'admin_products.js',
                'admin_categories.js',
            ],
        ];
    }
}