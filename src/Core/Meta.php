<?php

namespace App\Core;

/**
 * Manages page metadata and canonical URLs based on the application structure.
 */
class Meta
{
    /** Stores the metadata configuration loaded from the application settings. */
    private array $config;

    /**
     * Loads the metadata configuration.
     */
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/meta.php';
    }

    /**
     * Returns the metadata for a given section and action.
     *
     * Supports nested sections and falls back to the default metadata
     * when the requested section does not exist.
     */
    public function getMeta(string $section, string $action = 'index'): array
    {
        $default = $this->config['defaults'];

        $parts = explode('.', $section);

        $meta = $this->config;

        // Traverse the configuration according to the section hierarchy.
        foreach ($parts as $part) {
            if (!isset($meta[$part])) {
                return $default;
            }

            $meta = $meta[$part];
        }

        // Merge default metadata with the metadata defined for the requested action.
        $meta = array_merge(
            $default,
            $meta[$action] ?? []
        );

        // Build the canonical URL for the current page.
        $canonicalUrl = BASE_URL;

        if (
            !empty($_GET['url']) &&
            $_GET['url'] !== 'accueil'
        ) {
            $canonicalUrl .=
                '/?url=' .
                urlencode($_GET['url']);
        }

        $meta['canonical'] = $canonicalUrl;

        return $meta;
    }

    /**
     * Returns the configured site name.
     */
    public function siteName(): string
    {
        return $this->config['site_name'];
    }
}