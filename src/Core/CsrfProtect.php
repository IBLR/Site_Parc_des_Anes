<?php

namespace App\Core;

/**
 * Provides CSRF token generation, validation, and renewal for forms.
 */
class CsrfProtect
{
    /**
     * Generates or retrieves the CSRF token associated with a form.
     * Reuses the existing token when one is already available for the form.
     */
    public static function generateToken(string $formName): string
    {
        /** Initialize the CSRF token storage when it does not exist. */
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }

        // Reuse the existing token when available.
        if (!isset($_SESSION['csrf_tokens'][$formName])) {
            $_SESSION['csrf_tokens'][$formName] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_tokens'][$formName];
    }

    /**
     * Verifies the CSRF token associated with a form.
     * The token is removed after successful validation to ensure single use.
     */
    public static function verifyToken(string $formName, ?string $token): bool
    {
        if (
            $token === null ||
            !isset($_SESSION['csrf_tokens'][$formName])
        ) {
            return false;
        }

        if (!hash_equals($_SESSION['csrf_tokens'][$formName], $token)) {
            return false;
        }

        // Remove the token after successful validation.
        unset($_SESSION['csrf_tokens'][$formName]);

        return true;
    }

    /**
     * Generates a new CSRF token for a form.
     * The existing token is replaced with a newly generated one.
     */
    public static function refreshToken(string $formName): string
    {
        /** Initialize the CSRF token storage when it does not exist. */
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }

        $_SESSION['csrf_tokens'][$formName] = bin2hex(random_bytes(32));

        return $_SESSION['csrf_tokens'][$formName];
    }
}