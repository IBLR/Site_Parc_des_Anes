<?php

namespace App\Controllers;

use App\Core\Meta;
use App\Core\Logger;

/**
 * Provides shared functionality for application controllers.
 *
 * Handles view rendering, metadata preparation, section loading,
 * and administrator access control.
 */
class BaseController
{
    /**
     * Renders a complete page with its metadata, header, content, and footer.
     *
     * Derives the metadata section and action from the view path before
     * preparing the data required by the main layout.
     */
    protected function renderViewAndMeta(string $view, array $data = [])
    {
        // Convert the view path into the corresponding metadata path.
        $parts = explode('/', $view);

        // Use the last path element as the action.
        $action = array_pop($parts);

        // Use the remaining directories as the metadata section.
        $section = implode('.', $parts);

        // Retrieve metadata for the requested section and action.
        $metaService = new Meta();
        $meta = $metaService->getMeta($section, $action);

        // Build the complete page title from the site name and page title.
        $siteName = $metaService->siteName();
        $pageTitle = trim($meta['title'] ?? '');

        $title = ($pageTitle === '' || $pageTitle === $siteName)
            ? $siteName
            : $siteName . ' - ' . $pageTitle;

        // Prepare default variables required by the view and layout.
        $data['meta'] = $meta;
        $data['title'] = $title;
        $data['internalStyles'] = $data['internalStyles'] ?? [];
        $data['externalStyles'] = $data['externalStyles'] ?? [];
        $data['internalScripts'] = $data['internalScripts'] ?? [];
        $data['externalScripts'] = $data['externalScripts'] ?? [];
        $data['headerView'] = $data['headerView'] ?? 'header';

        extract($data);

        // Render the header view.
        ob_start();
        require __DIR__ . '/../Views/partials/' . $headerView . '.php';
        $header = ob_get_clean();

        // Render the main content view.
        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();

        // Render the footer view.
        ob_start();
        require __DIR__ . '/../Views/partials/footer.php';
        $footer = ob_get_clean();

        // Render the complete page through the main layout.
        require __DIR__ . '/../Views/layouts/main.php';
    }

    /**
     * Renders a requested section when its view file exists.
     *
     * Missing sections are logged and displayed in development mode
     * without interrupting the page rendering process.
     */
    protected function renderSection(string $viewDir, string $section, array $data = []): void
    {
        $file = $viewDir . '/sections/' . $section . '.php';

        if (file_exists($file)) {
            extract($data);
            require $file;
            return;
        }

        Logger::message(
            "Section manquante : {$section} | Fichier attendu : {$file}"
        );

        if (defined('DEV_MODE') && DEV_MODE) {
            echo "Section manquante : {$section} | Fichier attendu : {$file}";
        }
    }

    /**
     * Determines whether the current user has administrator privileges.
     */
    protected function isAdmin(): bool
    {
        return $_SESSION['user']['isAdmin'] ?? false;
    }

    /**
     * Prevents access to administrator pages for non-administrator users.
     *
     * Returns an HTTP 403 response and stops execution when access is denied.
     */
    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            exit('Accès refusé');
        }
    }
}