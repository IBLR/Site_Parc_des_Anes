<?php

namespace App\Core;

use App\Controllers\Home\HomeController;
use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;
use App\Controllers\Donkeys\DonkeysController;
use App\Controllers\Contact\ContactController;
use App\Controllers\Shop\ProductController;
use App\Controllers\Api\ApiProductController;
use App\Controllers\Cart\CartController;
use App\Controllers\Donation\DonationController;
use App\Controllers\Legal\LegalController;

use App\Controllers\Admin\AdminHomeController;
use App\Controllers\Admin\AdminProductController;
use App\Controllers\Admin\AdminCategoryController;
use App\Controllers\Admin\AdminContactController;
use App\Controllers\Admin\AdminOrderController;
use App\Controllers\Admin\AdminDonationController;

use App\Controllers\System\UnderConstructController;

/**
 * Routes incoming URLs to their corresponding controllers and actions.
 */
class Router
{
    /**
     * Checks whether a user is authenticated.
     *
     * Stores the requested route before redirecting unauthenticated users
     * to the login page so it can be restored after authentication.
     */
    private function checkAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            // Store the requested route before redirecting to the login page.
            if (!isset($_GET['url']) || $_GET['url'] !== 'connexion') {
                $_SESSION['redirect_after_login'] = $_GET['url'] ?? 'accueil';
            }

            header('Location: ' . BASE_URL . '/?url=connexion');
            exit;
        }
    }

    /**
     * Resolves the requested URL and executes the corresponding controller action.
     */
    public function renderPage(string $url): void
    {
        // Remove leading and trailing slashes from the requested URL.
        $url = trim($url, '/');

        switch ($url) {

            // Home page.
            case '':
            case 'accueil':
                (new HomeController())->index();
                break;

            // Authentication pages.
            case 'connexion':
                (new AuthController())->loginUser();
                break;

            case 'deconnexion':
                (new AuthController())->logoutUser();
                break;

            case 'inscription':
                (new AuthController())->registerUser();
                break;

            // Account pages.
            case 'compte':
                $this->checkAuth();
                (new UserController())->manageAccount();
                break;

            case 'compte/update-password':
                $this->checkAuth();
                (new UserController())->updatePassword();
                break;

            // Donkeys page.
            case 'nos-anes':
                (new DonkeysController())->index();
                break;

            // Contact pages.
            case 'contact':
                (new ContactController())->index();
                break;

            case 'contact/send':
                (new ContactController())->sendContactForm();
                break;

            // Shop page.
            case 'boutique':
                (new ProductController())->index();
                break;

            // Product API.
            case 'api/products':
                (new ApiProductController())->index();
                break;

            // Cart pages.
            case 'panier':
                (new CartController())->index();
                break;

            case 'panier/send':
                (new CartController())->sendCartForm();
                break;

            // Donation pages.
            case 'don':
                (new DonationController())->index();
                break;

            case 'don/checkout':
                (new DonationController())->checkout();
                break;

            case 'don/send':
                (new DonationController())->sendDonationForm();
                break;

            // Privacy policy page.
            case 'politique-confidentialite':
                (new LegalController())->privacy();
                break;

            // Legal notice page.
            case 'mentions-legales':
                (new LegalController())->legalNotice();
                break;

            // Terms and conditions page.
            case 'cgv':
                (new LegalController())->terms();
                break;

            // Administration dashboard and product management.
            case 'admin':
                $this->checkAuth();
                (new AdminHomeController())->index();
                break;

            case 'admin/produits':
                $this->checkAuth();
                (new AdminProductController())->index();
                break;

            case 'admin/produits/create':
                $this->checkAuth();
                (new AdminProductController())->createItem();
                break;

            case 'admin/produits/update':
                $this->checkAuth();
                (new AdminProductController())->updateItem();
                break;

            case 'admin/produits/delete':
                $this->checkAuth();
                (new AdminProductController())->deleteItem();
                break;

            // Administration dashboard and category management.
            case 'admin/categories':
                $this->checkAuth();
                (new AdminCategoryController())->index();
                break;

            case 'admin/categories/create':
                $this->checkAuth();
                (new AdminCategoryController())->createItem();
                break;

            case 'admin/categories/update':
                $this->checkAuth();
                (new AdminCategoryController())->updateItem();
                break;

            case 'admin/categories/delete':
                $this->checkAuth();
                (new AdminCategoryController())->deleteItem();
                break;

            // Administration dashboard and received message management.
            case 'admin/contacts':
                $this->checkAuth();
                (new AdminContactController())->index();
                break;

            case 'admin/contacts/update-status':
                $this->checkAuth();
                (new AdminContactController())->updateStatus();
                break;

            case 'admin/contacts/show':
                $this->checkAuth();
                (new AdminContactController())->show(
                    (int) ($_GET['id'] ?? 0)
                );
                break;

            // Administration dashboard and order management.
            case 'admin/commandes':
                $this->checkAuth();
                (new AdminOrderController())->index();
                break;

            case 'admin/commandes/update-status':
                $this->checkAuth();
                (new AdminOrderController())->updateStatus();
                break;

            case 'admin/commandes/show':
                $this->checkAuth();
                (new AdminOrderController())->show(
                    (int) ($_GET['id'] ?? 0)
                );
                break;

            // Administration dashboard and donation management.
            case 'admin/dons':
                $this->checkAuth();
                (new AdminDonationController())->index();
                break;

            case 'admin/dons/update-status':
                $this->checkAuth();
                (new AdminDonationController())->updateStatus();
                break;

            case 'admin/dons/show':
                $this->checkAuth();
                (new AdminDonationController())->show(
                    (int) ($_GET['id'] ?? 0)
                );
                break;

            // Under-construction page.
            case 'system/en-construction':
                (new UnderConstructController())->index();
                break;

            // Handle unknown routes.
            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
                break;
        }
    }
}