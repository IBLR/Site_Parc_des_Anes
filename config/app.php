<?php

/**
 * Development mode flag.
 * true = development, false = production.
 */
define('DEV_MODE', false);

/**
 * Project root directory.
 */
define('ROOT_PATH', dirname(__DIR__));

/**
 * Directory used to store public application files.
 *
 * In development, the public directory is /public.
 * In production, the project root is the public directory.
 */
define(
    'PUBLIC_PATH',
    is_dir(ROOT_PATH . '/public')
        ? ROOT_PATH . '/public'
        : ROOT_PATH
);

/**
 * Directory used to store application logs.
 */
define('LOG_PATH', ROOT_PATH . '/logs');

/**
 * Filesystem path to the "uploads/products" directory.
 */
define('UPLOAD_PRODUCTS_DIR', PUBLIC_PATH . '/uploads/products/');

// Automatically detect the public directory URL.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";   // Protocol
$host = $_SERVER['HTTP_HOST'];   // Domain name or localhost
$script = dirname($_SERVER['SCRIPT_NAME']);   // Executed script path

/**
 * Base URL of the public directory.
 */
define('BASE_URL', rtrim($protocol . $host . $script, '/'));

/**
 * Public URL path to the "uploads/products" directory.
 */
define('UPLOAD_PRODUCTS_URL', 'uploads/products/');