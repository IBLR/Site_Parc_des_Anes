<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactModel;
use App\Core\Logger;

use Exception;

/**
 * Handles administration actions related to contact messages.
 */
class AdminContactController extends BaseController
{
    /** URL used for contact administration redirects. */
    private string $url;

    /** View path used to render the contact administration pages. */
    private string $path;

    /** Handles contact message database operations. */
    private ContactModel $model;


    /**
     * Initializes the controller and its required model.
     *
     * Administrator access is required before any contact action can be used.
     */
    public function __construct()
    {
        $this->requireAdmin();

        $this->url = 'admin/contacts';
        $this->path = 'admin/contacts';

        $this->model = new ContactModel();
    }


    /**
     * Displays the list of contact messages.
     */
    public function index()
    {
        try {
            $messages = $this->model->findAllForAdmin();

            $data = [
                'messages' => $messages
            ];

            return $this->renderViewAndMeta(
                $this->path . '/index',
                array_merge(
                    $data,
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
     * Displays the details of a contact message.
     *
     * Redirects to the message list when the requested message does not exist.
     */
    public function show(int $id)
    {
        try {
            $message = $this->model->findItemById($id);

            if (!$message) {
                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=' .
                    $this->url
                );

                exit;
            }

            $data = [
                'message' => $message
            ];

            return $this->renderViewAndMeta(
                $this->path . '/show',
                array_merge(
                    $data,
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
     * Updates the processing status of a contact message.
     *
     * Only the statuses explicitly allowed by the controller are accepted.
     */
    public function updateStatus()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=' .
                    $this->url
                );

                exit;
            }

            $id = (int) ($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';

            /** Defines the statuses accepted for contact messages. */
            $allowedStatuses = [
                'pending',
                'processed'
            ];

            if (
                $id <= 0 ||
                !in_array($status, $allowedStatuses, true)
            ) {
                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=' .
                    $this->url
                );

                exit;
            }

            $this->model->updateStatus(
                $id,
                $status
            );

            header(
                'Location: ' .
                BASE_URL .
                '/?url=' .
                $this->url
            );

            exit;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during contact administration.
     *
     * Logs the exception, displays its details in development mode,
     * or stores the error in the session before redirecting to the contact page.
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
            '/?url=' .
            $this->url
        );

        exit;
    }


    /**
     * Returns the assetss required by the contact administration pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
                'admin-contacts.css',
            ],

            'internalScripts' => [
                'admin_contacts.js',
            ],
        ];
    }
}