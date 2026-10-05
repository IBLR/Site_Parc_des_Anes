<?php

namespace App\Controllers\Contact;

use App\Controllers\BaseController;
use App\Models\ContactModel;
use App\Core\FormValidator;
use App\Core\CsrfProtect;
use App\Core\Logger;

use Exception;

/**
 * Handles contact page display and contact form submission.
 */
class ContactController extends BaseController
{
    /**
     * Displays the contact page and prepares its required data.
     */
    public function index()
    {
        try {
            /** Generate the CSRF token used by the contact form. */
            $csrfToken = CsrfProtect::generateToken('contact');

            // Retrieve and clear the success flash message.
            $success = $_SESSION['success'] ?? null;
            unset($_SESSION['success']);

            /** Prepare the data required by the contact view. */
            $data = [
                'csrfToken' => $csrfToken,
                'errors' => [],
                'old' => [],
                'success' => $success,
            ];

            // Add the scripts and external assets required by the page.
            $data = array_merge(
                $data,
                $this->scriptsAndStylesAssets()
            );

            return $this->renderViewAndMeta(
                'contact/index',
                $data
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Processes and validates the submitted contact form.
     */
    public function sendContactForm()
    {
        // Only POST requests are allowed to submit the form.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?url=contact');
            exit;
        }

        try {
            // Verify the CSRF token before processing the submitted data.
            if (!CsrfProtect::verifyToken('contact', $_POST['csrf_token'] ?? null)) {

                $csrfToken = CsrfProtect::generateToken('contact');

                /** Prepare the form data required after a CSRF validation failure. */
                $data = [
                    'csrfToken' => $csrfToken,
                    'errors' => [
                        'csrf' => 'Une erreur est survenue lors de l’envoi du formulaire. Veuillez réessayer.'
                    ],
                    'old' => [
                        'last_name'  => $_POST['last_name'] ?? '',
                        'first_name' => $_POST['first_name'] ?? '',
                        'email'      => $_POST['email'] ?? '',
                        'phone'      => $_POST['phone'] ?? '',
                        'message'    => $_POST['message'] ?? ''
                    ],
                    'success' => null
                ];

                // Add the scripts and external assets required by the page.
                $data = array_merge(
                    $data,
                    $this->scriptsAndStylesAssets()
                );

                return $this->renderViewAndMeta(
                    'contact/index',
                    $data
                );
            }

            // Retrieve and trim the submitted form values.
            $lastName = trim($_POST['last_name'] ?? '');
            $firstName = trim($_POST['first_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $message = trim($_POST['message'] ?? '');

            // Validate the submitted form values.
            $validator = new FormValidator();

            $errors = $validator->validate([
                'last_name' => $lastName,
                'first_name' => $firstName,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
            ]);

            // Redisplay the form with the submitted values when validation fails.
            if (!empty($errors)) {

                $csrfToken = CsrfProtect::generateToken('contact');

                /** Prepare the data required to redisplay the form with validation errors. */
                $data = [
                    'errors' => $errors,
                    'old' => [
                        'last_name' => $lastName,
                        'first_name' => $firstName,
                        'email' => $email,
                        'phone' => $phone,
                        'message' => $message
                    ],
                    'csrfToken' => $csrfToken,
                    'success' => null
                ];

                // Add the scripts and external assets required by the page.
                $data = array_merge(
                    $data,
                    $this->scriptsAndStylesAssets()
                );

                return $this->renderViewAndMeta(
                    'contact/index',
                    $data
                );
            }

            // Normalize the phone number before saving it.
            if ($phone !== '') {
                $phone = $validator->normalizeFrenchPhone($phone);
            }

            // Check whether the same contact message has already been submitted.
            $model = new ContactModel();

            if ($model->exists($email, $message)) {
                $_SESSION['success'] = "Votre message a déjà été envoyé.";

                header('Location: ' . BASE_URL . '/?url=contact');
                exit;
            }

            // Save the validated contact form data to the database.
            $model->saveContactForm(
                $lastName,
                $firstName,
                $email,
                $phone,
                $message
            );

            // Store the success message as a flash message.
            $_SESSION['success'] = "Votre message a bien été envoyé.";

            // Redirect to prevent the form from being submitted again.
            header('Location: ' . BASE_URL . '/?url=contact');
            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    
    /**
     * Handles exceptions raised during contact operations.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] =
            'Une erreur est survenue. Veuillez réessayer.';

        header('Location: ' . BASE_URL . '/?url=contact');
        exit;
    }


    /**
     * Returns the internal scripts and external assets required by the contact page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'contact.css',
            ],

            'internalScripts' => [
                'contact_map.js',
                'form_validator.js',
                'contact_form.js',
            ],

            'externalStyles' => [
                [
                    'href' => 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                    'integrity' => 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=',
                    'crossorigin' => 'anonymous'
                ],
            ],

            'externalScripts' => [
                [
                    'src' => 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                    'integrity' => 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=',
                    'crossorigin' => 'anonymous'
                ],
            ]
        ];
    }
}