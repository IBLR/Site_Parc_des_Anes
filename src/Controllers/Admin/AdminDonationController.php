<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DonationModel;
use App\Core\Logger;

use Exception;

/**
 * Handles administration actions related to donations.
 */
class AdminDonationController extends BaseController
{
    /** URL used for donation administration redirects. */
    private string $url;

    /** View path used to render the donation administration pages. */
    private string $path;

    /** Handles donation database operations. */
    private DonationModel $model;


    /**
     * Initializes the controller and its required model.
     *
     * Administrator access is required before any donation action can be used.
     */
    public function __construct()
    {
        $this->requireAdmin();

        $this->url = 'admin/dons';
        $this->path = 'admin/donations';

        $this->model = new DonationModel();
    }


    /**
     * Displays the list of received donations.
     */
    public function index()
    {
        try {
            $donations = $this->model->findAllForAdmin();

            $data = [
                'donations' => $donations
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
     * Displays the details of a donation.
     *
     * Redirects to the donation list when the requested donation does not exist.
     */
    public function show(int $id)
    {
        try {
            $donation = $this->model->findItemById($id);

            if (!$donation) {
                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=' .
                    $this->url
                );

                exit;
            }

            $data = [
                'donation' => $donation
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
     * Updates the processing status of a donation.
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

            /** Defines the statuses accepted for donations. */
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
     * Handles exceptions raised during donation administration.
     *
     * Logs the exception, displays its details in development mode,
     * or stores the error in the session before redirecting to the donation page.
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
     * Returns the assetss required by the donation administration pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
                'admin-donations.css',
            ],

            'internalScripts' => [
                'admin_donations.js',
            ],
        ];
    }
}