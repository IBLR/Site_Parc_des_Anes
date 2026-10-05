<?php

namespace App\Controllers\Donation;

use App\Controllers\BaseController;
use App\Models\DonationModel;
use App\Models\UserModel;
use App\Core\FormValidator;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles donation pages and donation form submissions.
 */
class DonationController extends BaseController
{
    /**
     * Displays the main donation page.
     */
    public function index()
    {
        try {
            // Reset any previous donation state before starting a new donation.
            unset($_SESSION['donation']);
            unset($_SESSION['donation_success']);

            /** Generate the CSRF token used by the donation form. */
            $csrfToken = CsrfProtect::generateToken('donation');

            /** Prepare the data required by the donation view. */
            $data = [
                'csrfToken' => $csrfToken,
                'errors' => [],
                'old' => [],
                'success' => null,
            ];

            return $this->renderViewAndMeta(
                'donation/index',
                array_merge(
                    $data,
                    $this->scriptsAndStylesAssets(),
                )
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Displays the checkout page and validates the selected donation amount.
     *
     * GET: displays the checkout after a successful donation or
     * displays the current checkout.
     * POST: validates the donation amount submitted from the donation page.
     */
    public function checkout()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // Display the success message after a completed donation.
                if (!empty($_SESSION['donation_success'])) {

                    $success = $_SESSION['donation_success'];
                    unset($_SESSION['donation_success']);

                    /** Prepare the completed donation data for the checkout view. */
                    $data = [
                        'csrfToken' => CsrfProtect::generateToken('donation'),
                        'errors' => [],
                        'old' => [],
                        'amount' => 0,
                        'success' => $success,
                        'completed' => true,
                    ];

                    return $this->renderViewAndMeta(
                        'donation/checkout',
                        array_merge(
                            $data,
                            $this->scriptsAndStylesAssets(),
                            [
                                'headerView' => 'header_donation'
                            ]
                        )
                    );
                }

                // Redirect to the donation page when no checkout is in progress.
                if (
                    empty($_SESSION['donation']['amount'])
                    || (float) $_SESSION['donation']['amount'] <= 0
                ) {
                    header('Location: ' . BASE_URL . '/?url=don');
                    exit;
                }

                // Retrieve the donation amount stored for the current checkout.
                $amount = (float) $_SESSION['donation']['amount'];

                $email = '';

                // Retrieve the email address of the authenticated user.
                if (!empty($_SESSION['user']['id'])) {

                    $userId = (int) $_SESSION['user']['id'];

                    $userModel = new UserModel();

                    $user = $userModel->findUserById($userId);

                    if (!$user) {

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

                /** Generate the CSRF token used by the checkout form. */
                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the current checkout data for the view. */
                $data = [
                    'csrfToken' => $csrfToken,
                    'errors' => [],
                    'old' => [
                        'last_name'  => '',
                        'first_name' => '',
                        'email'      => $email,
                        'phone'      => '',
                        'comment'    => ''
                    ],
                    'amount' => $amount,
                    'success' => null,
                    'completed' => false,
                    'isAuthenticated' => !empty($_SESSION['user']['id']),
                ];

                return $this->renderViewAndMeta(
                    'donation/checkout',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_donation'
                        ]
                    )
                );
            }

            // Only POST requests are allowed for donation amount submission.
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/?url=don');
                exit;
            }

            // Verify the CSRF token before processing the submitted amount.
            if (!CsrfProtect::verifyToken(
                'donation',
                $_POST['csrf_token'] ?? null
            )) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the data required after a CSRF validation failure. */
                $data = [
                    'csrfToken' => $csrfToken,
                    'errors' => [
                        'csrf' =>
                            'Une erreur est survenue lors de l’envoi du formulaire. Veuillez réessayer.'
                    ],
                    'old' => [],
                    'success' => null,
                ];

                return $this->renderViewAndMeta(
                    'donation/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets()
                    )
                );
            }

            // Retrieve the donation amount submitted by the user.
            $amount = trim($_POST['donation_amount'] ?? '');

            // Validate that the submitted amount is numeric and greater than zero.
            if (
                $amount === ''
                || !is_numeric($amount)
                || (float) $amount <= 0
            ) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the data required to redisplay the donation form. */
                $data = [
                    'csrfToken' => $csrfToken,
                    'errors' => [
                        'amount' =>
                            'Merci de choisir un montant valide pour votre don.'
                    ],
                    'old' => [],
                    'success' => null,
                ];

                return $this->renderViewAndMeta(
                    'donation/index',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets()
                    )
                );
            }

            // Convert the validated amount to a numeric value.
            $amount = (float) $amount;

            // Store the selected amount temporarily for the checkout process.
            $_SESSION['donation'] = [
                'amount' => $amount,
                'completed' => false,
            ];

            // Prepare the checkout page for the selected donation amount.
            $email = '';

            if (!empty($_SESSION['user']['id'])) {

                $userId = (int) $_SESSION['user']['id'];

                $userModel = new UserModel();

                $user = $userModel->findUserById($userId);

                if (!$user) {

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

            $csrfToken = CsrfProtect::generateToken('donation');

            /** Prepare the checkout data for the view. */
            $data = [
                'csrfToken' => $csrfToken,

                'errors' => [],

                'old' => [
                    'last_name'  => '',
                    'first_name' => '',
                    'email'      => $email,
                    'phone'      => '',
                    'comment'    => ''
                ],

                'amount' => $amount,
                'success' => null,
                'isAuthenticated' => !empty($_SESSION['user']['id']),
            ];

            return $this->renderViewAndMeta(
                'donation/checkout',
                array_merge(
                    $data,
                    $this->scriptsAndStylesAssets(),
                    [
                        'headerView' => 'header_donation'
                    ]
                )
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Processes and saves the submitted donation form.
     */
    public function sendDonationForm()
    {
        try {

            // Only POST requests are allowed to submit the donation form.
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ' . BASE_URL . '/?url=don');
                exit;
            }

            // Require the user to be authenticated before submitting a donation.
            if (empty($_SESSION['user']['id'])) {

                // Store the page to display after successful authentication.
                $_SESSION['redirect_after_login'] = 'don/checkout';

                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=connexion'
                );

                exit;
            }

            // Admin accounts cannot submit a donation.
            if (!empty($_SESSION['user']['isAdmin'])) {
                header('Location: ' . BASE_URL . '/?url=don/checkout');
                exit;
            }

            // Retrieve the currently authenticated user.
            $userId = (int) $_SESSION['user']['id'];

            $userModel = new UserModel();

            $user = $userModel->findUserById($userId);

            if (!$user) {

                session_destroy();

                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=connexion'
                );

                exit;
            }

            // Use the email address stored in the database.
            $email = $user['email'];

            // Verify the CSRF token before processing the form.
            if (!CsrfProtect::verifyToken(
                'donation',
                $_POST['csrf_token'] ?? null
            )) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the submitted data after a CSRF validation failure. */
                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'csrf' =>
                            'Une erreur est survenue lors de l’envoi du formulaire. Veuillez réessayer.'
                    ],

                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email' => $email,
                        'phone'      => $_POST['phone'] ?? '',
                        'comment'    => $_POST['comment'] ?? ''
                    ],

                    'amount' =>
                        $_SESSION['donation']['amount'] ?? null,

                    'success' => null,
                ];

                return $this->renderViewAndMeta(
                    'donation/checkout',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_donation'
                        ]
                    )
                );
            }

            // Prevent an already completed donation from being submitted again.
            if (
                !empty($_SESSION['donation']['completed'])
            ) {
                header('Location: ' . BASE_URL . '/?url=don/checkout');
                exit;
            }

            // Ensure that a valid donation amount is still stored in the session.
            if (
                empty($_SESSION['donation']['amount'])
                || (float) $_SESSION['donation']['amount'] <= 0
            ) {

                header('Location: ' . BASE_URL . '/?url=don');
                exit;
            }

            $amount = (float) $_SESSION['donation']['amount'];

            // Retrieve and trim the submitted donor information.
            $lastName = trim($_POST['last_name'] ?? '');
            $firstName = trim($_POST['first_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $comment = trim($_POST['comment'] ?? '');

            // Validate the submitted donor information.
            $validator = new FormValidator();

            $errors = $validator->validate([
                'last_name'  => $lastName,
                'first_name' => $firstName,
                'email'      => $email,
                'phone'      => $phone
            ]);

            // Redisplay the checkout when validation fails.
            if (!empty($errors)) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the checkout data with validation errors and submitted values. */
                $data = [
                    'errors' => $errors,

                    'old' => [
                        'last_name'  => $lastName,
                        'first_name' => $firstName,
                        'email'      => $email,
                        'phone'      => $phone,
                        'comment'    => $comment
                    ],

                    'csrfToken' => $csrfToken,
                    'amount' => $amount,
                    'success' => null,
                    'isAuthenticated' => true,
                ];

                return $this->renderViewAndMeta(
                    'donation/checkout',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_donation'
                        ]
                    )
                );
            }

            // Normalize the phone number before saving the donation.
            if ($phone !== '') {
                $phone = $validator->normalizeFrenchPhone($phone);
            }

            // Create the donation record.
            $model = new DonationModel();

            $donationId = $model->createDonation(
                $userId,
                $lastName,
                $firstName,
                $email,
                $phone,
                $amount,
                $comment
            );

            // Redisplay the checkout when the database operation fails.
            if ($donationId === false) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the checkout data after a database error. */
                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'database' =>
                            'Une erreur est survenue lors de l’enregistrement de votre don. Veuillez réessayer.'
                    ],

                    'old' => [
                        'last_name'  => $lastName,
                        'first_name' => $firstName,
                        'email'      => $email,
                        'phone'      => $phone,
                        'comment'    => $comment
                    ],

                    'amount' => $amount,
                    'success' => null,
                    'isAuthenticated' => true,
                ];

                return $this->renderViewAndMeta(
                    'donation/checkout',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_donation'
                        ]
                    )
                );
            }

            // Retrieve the donation that was just created.
            $donation = $model->findItemById($donationId);

            if ($donation === false) {

                $csrfToken = CsrfProtect::generateToken('donation');

                /** Prepare the checkout data after a donation retrieval error. */
                $data = [
                    'csrfToken' => $csrfToken,

                    'errors' => [
                        'database' =>
                            'Une erreur est survenue lors de la récupération de votre don. Veuillez réessayer.'
                    ],

                    'old' => [
                        'last_name'  => $lastName,
                        'first_name' => $firstName,
                        'email'      => $email,
                        'phone'      => $phone,
                        'comment'    => $comment
                    ],

                    'amount' => $amount,
                    'success' => null,
                    'isAuthenticated' => true,
                ];

                return $this->renderViewAndMeta(
                    'donation/checkout',
                    array_merge(
                        $data,
                        $this->scriptsAndStylesAssets(),
                        [
                            'headerView' => 'header_donation'
                        ]
                    )
                );
            }

            // Format the amount actually stored in the database for the success message.
            $donationAmount = number_format(
                (float) $donation['amount'],
                2,
                ',',
                ' '
            );

            $_SESSION['donation_success'] =
                'Votre don de '
                . $donationAmount
                . ' € a bien été enregistré. Merci pour votre contribution !';

            // Mark the donation process as completed.
            $_SESSION['donation'] = [
                'amount' => 0,
                'completed' => true,
            ];

            // Redirect after POST to prevent the form from being submitted again.
            header('Location: ' . BASE_URL . '/?url=don/checkout');
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    
    /**
     * Cancels the current checkout and allows a new donation amount to be selected.
     */
    public function cancel()
    {
        try {
            // Clear the current donation state and success message.
            unset($_SESSION['donation']);
            unset($_SESSION['donation_success']);

            header('Location: ' . BASE_URL . '/?url=don');
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during donation operations.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] = $e->getMessage();

        header('Location: ' . BASE_URL . '/?url=don');
        exit;
    }


    /**
     * Returns the assetss required by the donation pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'donation.css',
                'donation-checkout.css',
            ],

            'internalScripts' => [
                'form_validator.js',
                'donation_form.js',
                'donation_checkout_form.js',
            ],
        ];
    }
}