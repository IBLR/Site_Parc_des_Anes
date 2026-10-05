<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles user authentication and account registration.
 */
class AuthController extends BaseController
{
    /**
     * Handles the user login process.
     *
     * Displays the login form and verifies the submitted credentials.
     */
    public function loginUser()
    {
        try {
            $error = null;

            // Generate a CSRF token for the login form.
            $csrfToken = CsrfProtect::generateToken('connexion');

            if ($_POST) {

                // Verify the CSRF token before processing the form.
                if (!CsrfProtect::verifyToken(
                    'connexion',
                    $_POST['csrf_token'] ?? null
                )) {
                    throw new Exception('Requête invalide.');
                }

                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                // Validate the email format.
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Email invalide";

                } else {
                    $userModel = new UserModel();
                    $user = $userModel->findUserByEmail($email);

                    if (
                        $user &&
                        password_verify(
                            $password,
                            $user['password']
                        )
                    ) {

                        // Renew the session ID after login
                        // to prevent session fixation attacks.
                        session_regenerate_id(true);

                        $_SESSION['user'] = [
                            // The password hash is never stored in the session.
                            'id' => $user['id'],
                            'email' => $user['email'],
                            'role' => $user['role'],
                            'isAdmin' => ($user['role'] === 'admin')
                        ];

                        // Redirect to the page requested before login.
                        $redirect =
                            $_SESSION['redirect_after_login']
                            ?? 'compte';

                        unset(
                            $_SESSION['redirect_after_login']
                        );

                        header(
                            'Location: ' .
                            BASE_URL .
                            '/index.php?url=' .
                            $redirect
                        );

                        exit;

                    } else {
                        $error = "Identifiants incorrects";
                    }
                }
            }

            $csrfToken = CsrfProtect::generateToken('connexion');

            $this->renderViewAndMeta(
                'auth/login/index',
                array_merge(
                    $this->scriptsAndStylesAssets(),
                    [
                        'error' => $error,
                        'csrfToken' => $csrfToken
                    ]
                )
            );

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Logs the current user out and destroys the session.
     */
    public function logoutUser()
    {
        try {
            session_unset();
            session_destroy();

            header(
                'Location: ' .
                BASE_URL .
                '/index.php?url=accueil'
            );

            exit;

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles the user registration process.
     *
     * Displays the registration form and creates a new user account
     * after validating the submitted information.
     */
    public function registerUser()
    {
        try {
            $error = null;

            // Generate a CSRF token for the registration form.
            $csrfToken = CsrfProtect::generateToken('register');

            if ($_POST) {

                // Verify the CSRF token before processing the form.
                if (!CsrfProtect::verifyToken(
                    'register',
                    $_POST['csrf_token'] ?? null
                )) {
                    throw new Exception('Requête invalide.');
                }

                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $userModel = new UserModel();

                // Check whether the email address is already registered.
                if ($userModel->findUserByEmail($email)) {
                    $error = "Cet email existe déjà";

                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Email invalide";

                } elseif (strlen($password) < 8) {
                    $error =
                        "Mot de passe trop court (8 caractères minimum)";

                } else {
                    // Hash the password before storing it in the database.
                    $passwordHash = password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );

                    $userModel->createUser(
                        $email,
                        $passwordHash
                    );

                    $_SESSION['success'] =
                        "Compte créé, vous pouvez vous connecter.";

                    header(
                        'Location: ' .
                        BASE_URL .
                        '/index.php?url=connexion'
                    );

                    exit;
                }
            }

            $csrfToken = CsrfProtect::generateToken('register');

            $this->renderViewAndMeta(
                'auth/register/index',
                array_merge(
                    $this->scriptsAndStylesAssets(),
                    [
                        'error' => $error,
                        'csrfToken' => $csrfToken
                    ]
                )
            );

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions that occur during authentication.
     *
     * Logs the exception and redirects the user to the login page
     * when the application is not running in development mode.
     */
    private function handleException(Exception $e): void
    {
        // Always log the exception for later analysis.
        Logger::error($e);

        // Display the original error during development.
        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] = $e->getMessage();

        header(
            'Location: ' .
            BASE_URL .
            '/index.php?url=connexion'
        );

        exit;
    }

    /**
     * Returns the assets required by the auth page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'auth.css',
            ],
        ];
    }
}