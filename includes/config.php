<?php
class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // إعدادات قاعدة البيانات من Render
        $this->host = "dpg-d3cp662li9vc73dpir7g-a.oregon-postgres.render.com";
        $this->port = "5432";
        $this->db_name = "mysql_database_ieiw";
        $this->username = "mysql_database_ieiw_user";
        $this->password = "k11NA72ExjZwLApd8JGveGrERbUxbXVu";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            // إنشاء اتصال PostgreSQL
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
            
            // تسجيل نجاح الاتصال
            error_log("✅ تم الاتصال بقاعدة البيانات بنجاح: " . $this->db_name);
            
        } catch(PDOException $exception) {
            // تسجيل خطأ الاتصال
            $error_message = "❌ فشل الاتصال بقاعدة البيانات: " . $exception->getMessage();
            error_log($error_message);
            
            // عرض رسالة خطأ مناسبة
            if (isset($_SESSION['admin_logged_in'])) {
                // للمستخدمين المسجلين، عرض تفاصيل الخطأ
                die("<div style='padding: 20px; background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; border-radius: 8px; margin: 20px;'>
                    <h3>❌ خطأ في الاتصال بقاعدة البيانات</h3>
                    <p><strong>التفاصيل:</strong> " . $exception->getMessage() . "</p>
                    <p><strong>الحل:</strong> تحقق من إعدادات قاعدة البيانات في Render</p>
                </div>");
            } else {
                // للزوار، عرض رسالة عامة
                die("عذراً، حدث خطأ في النظام. يرجى المحاولة لاحقاً.");
            }
        }
        return $this->conn;
    }

    // دالة لاختبار الاتصال
    public function testConnection() {
        try {
            $stmt = $this->conn->query("SELECT 1 as test_value, current_database() as db_name, current_user as db_user");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("✅ اختبار الاتصال ناجح - قاعدة البيانات: " . $result['db_name'] . " - المستخدم: " . $result['db_user']);
            return $result;
            
        } catch(PDOException $e) {
            error_log("❌ فشل اختبار الاتصال: " . $e->getMessage());
            return false;
        }
    }

    // دالة للحصول على معلومات قاعدة البيانات
    public function getDatabaseInfo() {
        try {
            $info = array();
            
            // معلومات الاتصال الأساسية
            $stmt = $this->conn->query("SELECT version() as postgres_version, current_database() as database_name, current_user as username");
            $info['basic'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // الجداول المتاحة
            $stmt = $this->conn->query("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = 'public' 
                ORDER BY table_name
            ");
            $info['tables'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $info;
            
        } catch(PDOException $e) {
            error_log("❌ فشل في الحصول على معلومات قاعدة البيانات: " . $e->getMessage());
            return false;
        }
    }
}

// إعدادات الجلسات
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// إعدادات الموقع
define('SITE_URL', 'https://dhlil-aljanoub-web.onrender.com');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$pdo = $database->getConnection();

// اختبار الاتصال تلقائياً (للتطوير)
if (isset($_GET['debug_db']) && $_GET['debug_db'] == 'test') {
    header('Content-Type: application/json; charset=utf-8');
    $test_result = $database->testConnection();
    $db_info = $database->getDatabaseInfo();
    
    echo json_encode([
        'connection_test' => $test_result ? 'success' : 'failed',
        'database_info' => $db_info,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// التحقق من وجود الجداول الأساسية عند أول طلب
if (!isset($_SESSION['db_checked'])) {
    try {
        $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'admin_users')");
        $admin_users_exists = $stmt->fetchColumn();
        
        if (!$admin_users_exists) {
            error_log("⚠️ تحذير: جدول admin_users غير موجود. يرجى استيراد قاعدة البيانات.");
        }
        
        $_SESSION['db_checked'] = true;
    } catch(PDOException $e) {
        error_log("❌ فشل في التحقق من الجداول: " . $e->getMessage());
    }
}
?>
