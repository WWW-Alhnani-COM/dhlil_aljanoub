<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // استخدام إعدادات Render
        $this->host = getenv("DB_HOST") ?: "localhost";
        $this->db_name = getenv("DB_NAME") ?: "main_database";
        $this->username = getenv("DB_USER") ?: "user";
        $this->password = getenv("DB_PASS") ?: "password";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات.");
        }
        return $this->conn;
    }
}

// إعدادات الموقع
define('SITE_URL', 'https://your-app-name.onrender.com');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();
?>
