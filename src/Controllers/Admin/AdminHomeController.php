<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Handles the administration dashboard.
 */
class AdminHomeController extends BaseController
{
    /**
     * Initializes the controller and requires administrator access.
     */
    public function __construct()
    {
        $this->requireAdmin();
    }

    
    /**
     * Displays the administration dashboard.
     */
    public function index()
    {
        $this->renderViewAndMeta(
            'admin/index',
            array_merge(
                $this->scriptsAndStylesAssets(),
                [
                    'headerView' => 'header_admin'
                ]
            )
        );
    }

    /**
     * Returns the assets required by the home administration page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'admin-header.css',
                'admin-home.css',
            ],
        ];
    }
}