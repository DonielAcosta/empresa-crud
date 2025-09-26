<?php

namespace App\Db;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Conn{
    private static ?PDO $instance = null;
    private static array $config = [];

    private function __construct(){

    }

    public static function getInstance(): PDO{
        if (self::$instance === null) {
            self::loadConfig();
            self::$instance = self::createConnection();
        }
        
        return self::$instance;
    }

    private static function loadConfig(): void{
        // Intentar cargar desde archivo .env primero
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            try {
                $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
                $dotenv->load();
            } catch (Exception $e) {
                // Si falla el .env, usar configuración por defecto
            }
        }

        // Cargar configuración desde archivo config.php como alternativa
        $configFile = __DIR__ . '/../../config.php';
        if (file_exists($configFile)) {
            $fileConfig = include $configFile;
            if (isset($fileConfig['database'])) {
                self::$config = $fileConfig['database'];
                return;
            }
        }

        // Configuración por defecto
        self::$config = [
            'host'    => $_ENV['DB_HOST'] ?? 'localhost',
            'port'    => $_ENV['DB_PORT'] ?? '3306',
            'dbname'  => $_ENV['DB_NAME'] ?? 'empresa_db',
            'user'    => $_ENV['DB_USER'] ?? 'datasis',
            'pass'    => $_ENV['DB_PASS'] ?? '1q2w3e..-',
            'charset' => 'utf8mb4'
        ];
    }

    private static function createConnection(): PDO{
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::$config['host'],
                self::$config['port'],
                self::$config['dbname'],
                self::$config['charset']
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            return new PDO($dsn, self::$config['user'], self::$config['pass'], $options);
        } catch (PDOException $e) {
            throw new PDOException("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function close(): void{
        self::$instance = null;
    }

    public static function getConfig(): array{
        if (empty(self::$config)) {
            self::loadConfig();
        }
        return self::$config;
    }
}
