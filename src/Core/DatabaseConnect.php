<?php

namespace App\Core;

// Import global PHP classes required outside the current namespace.
use PDO;
use PDOException;

/**
 * Manages the application's database connection using the Singleton pattern.
 */
class DatabaseConnect
{
    /** Stores the unique instance of the database connection manager. */
    private static ?DatabaseConnect $instance = null;

    /** Stores the active PDO database connection. */
    private PDO $connection;

    /**
     * Initializes the database connection.
     *
     * Loads the database configuration, builds the DSN, and creates
     * the PDO connection with secure default options.
     */
    private function __construct()
    {
        require __DIR__ . '/../../config/database.php';

        // Build the database connection string.
        $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";

        // Configure secure PDO connection options.
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->connection = new PDO(
                $dsn,
                $DB_USER,
                $DB_PASS,
                $options
            );
        } catch (PDOException $e) {

            // Log the database error before handling the response.
            Logger::error($e);

            if (defined('DEV_MODE') && DEV_MODE) {

                // Display detailed error information for development debugging.
                echo "<h2>Erreur PDO détectée</h2>";
                echo "<ul>";
                echo "<li><strong>Message :</strong> "
                    . htmlspecialchars($e->getMessage())
                    . "</li>";
                echo "<li><strong>Code :</strong> "
                    . htmlspecialchars($e->getCode())
                    . "</li>";
                echo "<li><strong>Fichier :</strong> "
                    . htmlspecialchars($e->getFile())
                    . "</li>";
                echo "<li><strong>Ligne :</strong> "
                    . htmlspecialchars($e->getLine())
                    . "</li>";
                echo "<li><strong>Trace :</strong> <pre>"
                    . htmlspecialchars($e->getTraceAsString())
                    . "</pre></li>";
                echo "</ul>";
            } else {

                // Display a generic message to the user in production.
                echo "Erreur de connexion à la base de données.";
            }

            exit;
        }
    }

    /**
     * Returns the unique instance of the database connection manager.
     * Creates the instance on first access and reuses it afterwards.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns the active PDO database connection.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Prevents cloning of the Singleton instance.
     */
    private function __clone(): void {}

    /**
     * Prevents deserialization of the Singleton instance.
     */
    public function __wakeup(): void
    {
        throw new \Exception(
            "Impossible de désérialiser un singleton"
        );
    }
}