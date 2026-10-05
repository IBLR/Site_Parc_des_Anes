<?php

namespace App\Controllers\Donkeys;

use App\Controllers\BaseController;
use App\Core\Logger;

use Exception;

/**
 * Handles the donkeys page.
 */
class DonkeysController extends BaseController
{
    /**
     * Displays the donkeys page.
     */
    public function index()
    {
        try {
            $this->renderViewAndMeta(
                'donkeys/index',
                $this->scriptsAndStylesAssets(),
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    
    /**
     * Handles exceptions raised during donkeys page operations.
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
     * Returns the assets required by the donkeys page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'donkeys.css',
            ],
        ];
    }
}