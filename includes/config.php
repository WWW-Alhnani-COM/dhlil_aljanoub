<?php
// config.php - نسخة جاهزة للعمل على Render

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // قراءة إعدادات قاعدة البيانات من Environment Variables
        $this->host = getenv("DB_HOST");
        $this->db_name = getenv("DB_NAME");
        $this->username = getenv("DB_USER");
        $this->password = getenv("DB_PASS");
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.");
        }
        return $this->conn;
    }
}

// إعدادات الموقع
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../admin/uploads/');

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();
?>
