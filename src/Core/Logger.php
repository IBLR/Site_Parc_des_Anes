<?php

namespace App\Core;

use Exception;

/**
 * Provides centralized application logging for exceptions and technical messages.
 */
class Logger
{
    /**
     * Logs an exception to the application error log.
     */
    public static function error(Exception $e): void
    {
        // Create the log directory when it does not exist.
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0700, true);
        }

        error_log(
            sprintf(
                "[%s] Message: %s | Code: %s | Fichier: %s | Ligne: %s%s",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getCode(),
                $e->getFile(),
                $e->getLine(),
                PHP_EOL
            ),
            3,
            LOG_PATH . '/app_errors.log'
        );
    }

    /**
     * Logs a technical message to the application error log.
     */
    public static function message(string $message): void
    {
        // Create the log directory when it does not exist.
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0700, true);
        }

        error_log(
            sprintf(
                "[%s] %s%s",
                date('Y-m-d H:i:s'),
                $message,
                PHP_EOL
            ),
            3,
            LOG_PATH . '/app_errors.log'
        );
    }
}