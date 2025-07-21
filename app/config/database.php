<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->loadEnvironmentVariables();
        
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'ecoride_db';
        $this->username = $_ENV['DB_USERNAME'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
    }

    private function loadEnvironmentVariables() {
        $envFile = __DIR__ . '/../../.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') === false) {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        } else {
            echo "Fichier .env non trouvé à : " . $envFile . "<br>";
        }
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch(PDOException $e) {
            if ($e->getCode() == 1049) {
                try {
                    $this->createDatabase();
                    $this->conn = new PDO(
                        "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                        $this->username,
                        $this->password,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false,
                        ]
                    );
                    echo "Base de données '{$this->db_name}' créée avec succès !<br>";
                } catch(PDOException $createError) {
                    if ($_ENV['APP_ENV'] === 'production') {
                        die("Erreur de connexion à la base de données");
                    } else {
                        die("Erreur lors de la création de la base de données : " . $createError->getMessage());
                    }
                }
            } else {
                if ($_ENV['APP_ENV'] === 'production') {
                    die("Erreur de connexion à la base de données");
                } else {
                    die("Erreur de connexion : " . $e->getMessage());
                }
            }
        }
        return $this->conn;
    }

    private function createDatabase() {
        try {
            $tempConn = new PDO(
                "mysql:host=" . $this->host . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $sql = "CREATE DATABASE IF NOT EXISTS `{$this->db_name}` 
                    CHARACTER SET utf8mb4 
                    COLLATE utf8mb4_unicode_ci";
            
            $tempConn->exec($sql);
            $tempConn = null;
            
        } catch(PDOException $e) {
            throw new PDOException("Impossible de créer la base de données : " . $e->getMessage());
        }
    }

    public function testConnection() {
        $conn = $this->getConnection();
        if($conn) {
            echo " Connexion MySQL réussie !<br>";
            echo " Base de données : " . $this->db_name . "<br>";
            echo " Utilisateur : " . $this->username . "<br>";
            echo " Environnement : " . ($_ENV['APP_ENV'] ?? 'development') . "<br>";
            return true;
        }
        return false;
    }
}
?>