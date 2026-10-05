<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Core\Logger;

use Exception;

/**
 * Handles the under-construction page.
 */
class UnderConstructController extends BaseController
{
    /**
     * Displays the under-construction page.
     */
    public function index()
    {
        try {
            $this->renderViewAndMeta(
                'system/under_construction',
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during system page operations.
     */
    private function handleException(Exception $e): void
    {
        Logger::error($e);

        if (defined('DEV_MODE') && DEV_MODE) {
            die($e->getMessage());
        }

        $_SESSION['error'] = $e->getMessage();

        header('Location: ' . BASE_URL . '/?url=accueil');
        exit;
    }
}