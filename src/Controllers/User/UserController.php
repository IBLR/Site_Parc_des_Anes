<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\DonationModel;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles user account pages and account-related actions.
 */
class UserController extends BaseController
{
    /**
     * Displays the "Mon compte" page.
     *
     * This method handles GET requests.
     */
    public function manageAccount()
    {
        try {
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception('Non autorisé');
            }

            $userId = (int) $_SESSION['user']['id'];

            // Retrieve and clear account flash messages.
            $error = $_SESSION['account_error'] ?? null;
            $success = $_SESSION['account_success'] ?? null;

            unset(
                $_SESSION['account_error'],
                $_SESSION['account_success']
            );

            // Generate the CSRF token used by account forms.
            $csrfToken = CsrfProtect::generateToken('account');

            // Retrieve the user's order history.
            $orderModel = new OrderModel();

            $orders = $orderModel->findAllByUserId($userId);

            // Retrieve the user's donation history.
            $donationModel = new DonationModel();

            $donations = $donationModel->findAllByUserId($userId);

            /** Prepare the data required by the account view. */
            $data = [
                'error' => $error,
                'success' => $success,
                'csrfToken' => $csrfToken,

                'email' => $_SESSION['user']['email'],

                'canAccessAdmin' =>
                    ($_SESSION['user']['isAdmin'] ?? false),

                'orders' => $orders,
                'donations' => $donations
            ];

            // Add the scripts required by the account page.
            $data = array_merge(
                $data,
                $this->scriptsAndStylesAssets()
            );

            $this->renderViewAndMeta(
                'user/index',
                $data
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Processes a password change request.
     *
     * This method handles POST requests.
     */
    public function updatePassword()
    {
        // Only POST requests are allowed to change the password.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '/?url=compte'
            );

            exit;
        }

        try {
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception('Non autorisé');
            }

            // Verify the CSRF token before processing the request.
            if (!CsrfProtect::verifyToken(
                'account',
                $_POST['csrf_token'] ?? null
            )) {
                throw new Exception('Requête invalide');
            }

            // Retrieve the current authenticated user.
            $userModel = new UserModel();

            $user = $userModel->findUserById(
                (int) $_SESSION['user']['id']
            );

            if (!$user) {
                throw new Exception('Utilisateur introuvable');
            }

            // Retrieve the submitted password values.
            $currentPassword =
                $_POST['current_password'] ?? '';

            $newPassword =
                $_POST['new_password'] ?? '';

            $confirmPassword =
                $_POST['confirm_password'] ?? '';

            // Validate the current password and the new password values.
            if (
                !password_verify(
                    $currentPassword,
                    $user['password']
                )
            ) {
                $_SESSION['account_error'] =
                    'Mot de passe actuel incorrect';

            } elseif (strlen($newPassword) < 8) {
                $_SESSION['account_error'] =
                    'Le nouveau mot de passe est trop court (8 caractères minimum)';

            } elseif ($newPassword !== $confirmPassword) {
                $_SESSION['account_error'] =
                    'Les mots de passe ne correspondent pas';

            } else {
                // Generate a secure hash for the new password.
                $passwordHash = password_hash(
                    $newPassword,
                    PASSWORD_DEFAULT
                );

                $userModel->updateUserPassword(
                    $user['id'],
                    $passwordHash
                );

                $_SESSION['account_success'] =
                    'Mot de passe modifié avec succès';
            }

            // PRG: redirect after POST to prevent form resubmission.
            header(
                'Location: ' .
                BASE_URL .
                '/?url=compte'
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during account operations.
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
            '/?url=compte'
        );

        exit;
    }
    

    /**
     * Returns the assetss required by the account page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'account.css',
            ],

            'internalScripts' => [
                'account.js',
            ],
        ];
    }
}