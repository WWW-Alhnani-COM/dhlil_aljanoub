<?php
class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // استخدام إعدادات قاعدة البيانات من Render
        $this->host = getenv("DB_HOST") ?: "dpg-d3cp662li9vc73dpir7g-a";
        $this->port = getenv("DB_PORT") ?: "5432";
        $this->db_name = getenv("DB_NAME") ?: "mysql_database_ieiw";
        $this->username = getenv("DB_USER") ?: "mysql_database_ieiw_user";
        $this->password = getenv("DB_PASS") ?: "k11NA72ExjZwLApd8JGveGrERbUxbXVu";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, // تغيير إلى pgsql
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES 'UTF8'");
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            // عرض رسالة خطأ بسيطة للمستخدم
            die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.");
        }
        return $this->conn;
    }
}

// إعدادات الموقع
define('SITE_URL', 'https://dhlil-aljanoub-web.onrender.com');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();
