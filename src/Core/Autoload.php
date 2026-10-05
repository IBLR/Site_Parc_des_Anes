<?php

require_once __DIR__ . '/Logger.php';

/**
 * Automatically loads classes from the App namespace.
 *
 * Converts the class namespace into a file path and loads the corresponding
 * PHP file when it exists. Missing classes are logged for debugging purposes.
 */
spl_autoload_register(function (string $class): void {

    /** Namespace prefix handled by this autoloader. */
    $prefix = 'App\\';

    /** Base directory used to resolve App namespace classes. */
    $baseDir = __DIR__ . '/../';

    // Ignore classes that do not belong to the App namespace.
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    /** Class path relative to the App namespace. */
    $relativeClass = substr($class, strlen($prefix));

    /** Expected file path for the requested class. */
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // Load the class file when it exists.
    if (is_file($file)) {
        require $file;
        return;
    }

    // Log the missing class and its expected file path.
    Logger::message(
        "Classe introuvable | Classe: {$class} | Fichier attendu: {$file}"
    );
});