<?php

namespace App\Controllers\Legal;

use App\Controllers\BaseController;
use App\Core\Logger;

use Exception;

/**
 * Handles the legal pages.
 */
class LegalController extends BaseController
{
    /**
     * Displays the privacy policy page.
     */
    public function privacy()
    {
        try {
            $this->renderViewAndMeta(
                'legal/privacy',
                $this->scriptsAndStylesAssets(),
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Displays the legal notice page.
     */
    public function legalNotice()
    {
        try {
            $this->renderViewAndMeta(
                'legal/legal-notice',
                $this->scriptsAndStylesAssets(),
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Displays the terms and conditions page.
     */
    public function terms()
    {
        try {
            $this->renderViewAndMeta(
                'legal/terms',
                $this->scriptsAndStylesAssets(),
            );
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Handles exceptions raised during legal page operations.
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
     * Returns the assets required by the legal page.
     */
    private function scriptsAndStylesAssets(): array
    {
        return [
            'internalStyles' => [
                'legal.css',
            ],
        ];
    }
}