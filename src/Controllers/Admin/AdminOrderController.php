<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Core\Logger;

use Exception;

/**
 * Handles administration actions related to orders.
 */
class AdminOrderController extends BaseController
{
    /** URL used for order administration redirects. */
    private string $url;

    /** View path used to render the order administration pages. */
    private string $path;

    /** Handles order database operations. */
    private OrderModel $model;

    /**
     * Initializes the controller and its required model.
     *
     * Administrator access is required before any order action can be used.
     */
    public function __construct()
    {
        $this->requireAdmin();

        $this->url = 'admin/commandes';
        $this->path = 'admin/orders';

        $this->model = new OrderModel();
    }


    /**
     * Displays the list of orders.
     */
    public function index()
    {
        try {
            $orders = $this->model->findAllForAdmin();

            $data = [
                'orders' => $orders
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
     * Displays the details of an order.
     *
     * Redirects to the order list when the requested order does not exist.
     */
    public function show(int $id)
    {
        try {
            $order = $this->model->findItemById($id);

            if (!$order) {
                header(
                    'Location: ' .
                    BASE_URL .
                    '/?url=' .
                    $this->url
                );

                exit;
            }

            $items = $this->model->findOrderItems($id);

            $data = [
                'order' => $order,
                'items' => $items
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
     * Updates the status of an order.
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

            /** Defines the statuses accepted for orders. */
            $allowedStatuses = [
                'pending',
                'shipped',
                'cancelled'
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
     * Handles exceptions raised during order administration.
     *
     * Logs the exception, displays its details in development mode,
     * or stores the error in the session before redirecting to the order page.
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
     * Returns the assetss required by the order administration pages.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
                'admin-orders.css',
            ],

            'internalScripts' => [
                'admin_orders.js',
            ],
        ];
    }
}