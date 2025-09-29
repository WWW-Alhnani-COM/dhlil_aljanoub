<?php
class Database {
    private $host = "dpg-d3cp662li9vc73dpir7g-a";
    private $port = "5432";
    private $db_name = "mysql_database_ieiw";
    private $username = "mysql_database_ieiw_user";
    private $password = "k11NA72ExjZwLApd8JGveGrERbUxbXVu";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("✅ تم الاتصال بقاعدة البيانات بنجاح");
        } catch(PDOException $exception) {
            $error_message = "❌ فشل الاتصال بقاعدة البيانات: " . $exception->getMessage();
            error_log($error_message);
            // عرض الخطأ للمطور
            if (isset($_SESSION['admin_logged_in'])) {
                die($error_message);
            } else {
                die("عذراً، حدث خطأ في الاتصال بالخادم.");
            }
        }
        return $this->conn;
    }
}

// ... باقي الكود
// إعدادات الموقع
define('SITE_URL', 'https://dhlil-aljanoub-web.onrender.com');
define('ADMIN_URL', SITE_URL . '../admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();
