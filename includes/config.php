<?php
class Database {
    // إعدادات محلية
    private $host = "localhost";
    private $db_name = "dhlil_aljanoub";
    private $username = "root"; // أو "postgres" حسب الإعداد
    private $password = ""; // كلمة المرور المحلية
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . 
                ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            // عرض تفاصيل الخطأ للتdebug محلياً
            die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات: " . $exception->getMessage());
        }
        return $this->conn;
    }
}

// إعدادات الموقع المحلي
define('SITE_URL', 'http://localhost/dhlil_aljanoub');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../admin/uploads/');

// تفعيل عرض الأخطاء للتطوير المحلي
error_reporting(E_ALL);
ini_set('display_errors', 1);

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();
?>