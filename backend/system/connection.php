<?php
require_once 'settings.php';
require_once  __DIR__ . '/../oauth/FileController.php';
require_once  __DIR__ . '/../oauth/UserController.php';

class Database {
    private static $instance = null;
    private $pdo;

    /**
     * Özel constructor, dışarıdan doğrudan örnek oluşturmayı engeller.
     */
    private function __construct() {
        global $host, $username, $password, $dbname;
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
            $this->pdo->exec("SET time_zone = '+03:00';");
        } catch (PDOException $e) {
            // Hata durumunda düz bir hata mesajı döndür ve logla
            error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
            die("Bağlantı hatası: " . $e->getMessage());
        }
    }

    /**
     * Singleton örneği döndürür.
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDO bağlantısını döndürür.
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * FilesController sınıfını başlatır.
     * @return FileController
     */
    public function bootstrap() {
        return [
            'UserController' => new UserController($this->pdo),
            'FileController' => new FileController($this->pdo)
        ];
    }
}
?>