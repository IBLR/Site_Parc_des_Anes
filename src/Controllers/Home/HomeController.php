<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Core\Logger;

use Exception;

/**
 * Handles the home page.
 */
class HomeController extends BaseController
{
    /**
     * Displays the home page.
     */
    public function index()
    {
        try {
            $this->renderViewAndMeta(
                'home/index',
                $this->scriptsAndStylesAssets(),
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    
    /**
     * Handles exceptions raised during home page operations.
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

    /**
     * Returns the assets required by the home page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'home.css',
            ],
        ];
    }
}
