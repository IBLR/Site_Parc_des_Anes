<?php

namespace App\Controllers\Cart;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Core\FormValidator;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles shopping cart display and order submission.
 */
class CartController extends BaseController
{
    /**
     * Displays the shopping cart and prepares the checkout form.
     *
     * Retrieves the connected user's email when available,
     * generates the CSRF token and prepares the data required by the view.
     */
    public function index()
    {
        try {
            // Retrieve the connected user when available.
            $email = '';

            if (!empty($_SESSION['user']['id'])) {

                $userId = (int) $_SESSION['user']['id'];

                $userModel = new UserModel();

                $user = $userModel->findUserById($userId);

                if (!$user) {
                    // Destroy an inconsistent session or a session linked to a deleted user.
                    session_destroy();

                    header(
                        'Location: ' .
                        BASE_URL .
                        '/?url=connexion'
                    );

                    exit;
                }

                $email = $user['email'];
            }


            // Generate the CSRF token required by the cart form.
            $csrfToken = CsrfProtect::generateToken('cart');


            // Retrieve and clear the success flash message.
            $success = $_SESSION['success'] ?? null;
            unset($_SESSION['success']);

            $completed = !empty($success);

            $data = [
                'csrfToken' => $csrfToken,
                'errors' => [],
                'old' => [
                    'email' => $email
                ],
                'success' => $success,
                'completed' => $completed,
                'isAuthenticated' => !empty($_SESSION['user']['id']),
            ];

            return $this->renderViewAndMeta(
                'cart/index',
                array_merge(
                    $data,
                    $this->scriptsAndStylesAssets(),
                    [
                        'headerView' => 'header_cart'
                    ]
                )
            );

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Processes the cart checkout form and creates the order.
     */
    public function sendCartForm()
    {
        // Only POST requests are allowed for order submission.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=panier');
            exit;
        }

        try {
            // -------------------------------------------------
            // Authentication check
            // -------------------------------------------------

            if (empty($_SESSION['user']['id'])) {

                // Store the requested page for the post-login redirect.
                $_SESSION['redirect_after_login'] = 'panier';

                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=connexion'
                );

                exit;
            }

            // Admin accounts cannot submit an order.
            if (!empty($_SESSION['user']['isAdmin'])) {
                header('Location: ' . BASE_URL . '/?url=panier');
                exit;
            }

            // Retrieve the currently authenticated user.
            $userId = (int) $_SESSION['user']['id'];

            $userModel = new UserModel();

            $user = $userModel->findUserById($userId);

            if (!$user) {
                // Destroy an inconsistent session or a session linked to a deleted user.
                session_destroy();

                header('Location: ' . BASE_URL . '/?url=connexion');
                exit;
            }

            // Use the email stored in the database instead of trusting the submitted value.
            $email = $user['email'];


            // -------------------------------------------------
            // CSRF verification
            // -------------------------------------------------

            if (!CsrfProtect::verifyToken('cart', $_POST['csrf_token'] ?? null)) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'csrf' => 'Une erreur est survenue lors de l’envoi du formulaire. Veuillez réessayer.'
                    ],

                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email'      => $email,
                        'phone'      => $_POST['phone'] ?? '',
                        'address'    => $_POST['address'] ?? '',
                        'postcode'   => $_POST['postcode'] ?? '',
                        'city'       => $_POST['city'] ?? ''
                    ],

                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -------------------------------------------------------
            // Retrieve and validate the submitted cart
            // -------------------------------------------------------

            $cartJson = $_POST['cart'] ?? '';
            $cart = json_decode($cartJson, true);

            if (!is_array($cart) || empty($cart)) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'cart' => 'Votre panier est vide. Impossible de valider la commande.'
                    ],

                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email'      => $email,
                        'phone'      => $_POST['phone'] ?? '',
                        'address'    => $_POST['address'] ?? '',
                        'postcode'   => $_POST['postcode'] ?? '',
                        'city'       => $_POST['city'] ?? ''
                    ],

                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -----------------------------------------------------------------
            // Validate cart contents against the SQL database
            // -----------------------------------------------------------------

            $productModel = new ProductModel();

            $validatedCart = [];
            $unavailableProducts = [];
            $cartError = null;

            foreach ($cart as $item) {

                $productId = (int) ($item['id'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 0);

                // Enforce the maximum quantity allowed per product.
                if ($productId <= 0 || $quantity <= 0 || $quantity > 10) {
                    $cartError = 'Quantité invalide. Maximum 10 exemplaires par produit.';
                    break;
                }

                // Retrieve the product from the products table.
                $product = $productModel->findItemById($productId);

                if (!$product) {
                    $cartError = 'Un produit de votre panier est introuvable.';
                    break;
                }

                if ((int) $product['is_available'] !== 1) {
                    $unavailableProducts[] = $product['name'];
                    continue;
                }

                // Keep only product data retrieved from the database.
                $validatedCart[] = [
                    'id' => $productId,
                    'reference' => $product['reference'],
                    'name' => $product['name'],
                    'price' => (float) $product['price'],
                    'quantity' => $quantity
                ];
            }


            if ($cartError !== null) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'cart' => $cartError
                    ],

                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email'      => $email,
                        'phone'      => $_POST['phone'] ?? '',
                        'address'    => $_POST['address'] ?? '',
                        'postcode'   => $_POST['postcode'] ?? '',
                        'city'       => $_POST['city'] ?? ''
                    ],

                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            if (!empty($unavailableProducts)) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'unavailable_products' =>
                            'Produit(s) "'
                            . implode('", "', $unavailableProducts)
                            . '" non disponible(s).'
                    ],

                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email'      => $email,
                        'phone'      => $_POST['phone'] ?? '',
                        'address'    => $_POST['address'] ?? '',
                        'postcode'   => $_POST['postcode'] ?? '',
                        'city'       => $_POST['city'] ?? ''
                    ],

                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -----------------------------------------------------------------
            // Retrieve and clean the submitted form data
            // -----------------------------------------------------------------

            $lastName = trim($_POST['last_name'] ?? '');
            $firstName = trim($_POST['first_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $postcode = trim($_POST['postcode'] ?? '');
            $city = trim($_POST['city'] ?? '');


            // -------------------------------------------------
            // Validate the submitted form data
            // -------------------------------------------------

            $validator = new FormValidator();

            $errors = $validator->validate([
                'last_name' => $lastName,
                'first_name' => $firstName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'postcode' => $postcode,
                'city' => $city,
            ]);


            // -------------------------------------------------
            // Redisplay the form when validation fails
            // -------------------------------------------------

            if (!empty($errors)) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'errors' => $errors,

                    'old' => [
                        'last_name' => $lastName,
                        'first_name' => $firstName,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'postcode' => $postcode,
                        'city' => $city
                    ],

                    'csrfToken' => $csrfToken,
                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -------------------------------------------------
            // Normalize the phone number
            // -------------------------------------------------

            if ($phone !== '') {
                $phone = $validator->normalizeFrenchPhone($phone);
            }


            // -------------------------------------------------
            // Create the order
            // -------------------------------------------------

            $model = new OrderModel();

            $orderId = $model->createOrder(
                $userId,
                $lastName,
                $firstName,
                $email,
                $phone,
                $address,
                $postcode,
                $city,
                $validatedCart
            );


            // -------------------------------------------------
            // Verify that the order was successfully stored
            // -------------------------------------------------

            if ($orderId === false) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'database' =>
                            'Une erreur est survenue lors de l\'enregistrement de votre commande. Veuillez réessayer.'
                    ],

                    'old' => [
                        'last_name'  => $lastName,
                        'first_name' => $firstName,
                        'email'      => $email,
                        'phone'      => $phone,
                        'address'    => $address,
                        'postcode'   => $postcode,
                        'city'       => $city
                    ]
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -------------------------------------------------------
            // Calculate the actual order total
            // -------------------------------------------------------

            $orderTotal = 0;

            foreach ($validatedCart as $item) {
                $orderTotal += $item['price'] * $item['quantity'];
            }


            // -------------------------------------------------
            // Retrieve the order number
            // -------------------------------------------------

            $orderNumber = $model->findOrderNumberById($orderId);

            if ($orderNumber === false) {

                $csrfToken = CsrfProtect::generateToken('cart');

                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'database' =>
                            'Une erreur est survenue lors de la récupération du numéro de commande.'
                    ],

                    'old' => [
                        'last_name'  => $lastName,
                        'first_name' => $firstName,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'postcode' => $postcode,
                        'city' => $city
                    ],

                    'success' => null
                ];

                return $this->renderViewAndMeta(
                    'cart/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_cart'
                        ]
                    )
                );
            }


            // -------------------------------------------------
            // Store the success message and order ID in the session.
            // -------------------------------------------------

            $_SESSION['success'] =
                'Paiement accepté - Commande n°' . $orderNumber .
                ' enregistrée. Total : ' .
                number_format($orderTotal, 2, ',', ' ') . ' €.';

            $_SESSION['order_id'] = $orderId;


            // -------------------------------------------------
            // Redirect from POST to GET to avoid form resubmission.
            // -------------------------------------------------

            header('Location: ' . BASE_URL . '/?url=panier');
            exit;

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions that occur during cart operations.
     *
     * Logs the exception and redirects the user to the cart page
     * when the application is not running in development mode.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] =
            'Une erreur est survenue. Veuillez réessayer.';

        header('Location: ' . BASE_URL . '/?url=panier');
        exit;
    }

    
    /**
     * Returns the JavaScript assets required by the cart pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'cart.css',
            ],

            'internalScripts' => [
                'shop.js',
                'cart_products.js',
                'form_validator.js',
                'cart_submit.js',
                'cart_form.js',
            ],
        ];
    }

}