<?php
class Database {
    private $host = "dpg-d3cp662li9vc73dpir7g-a.oregon-postgres.render.com";
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
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات.");
        }
        return $this->conn;
    }
}
function checkDatabaseConnection() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT 1");
        error_log("✅ تم الاتصال بقاعدة البيانات بنجاح - " . date('Y-m-d H:i:s'));
        return true;
    } catch (PDOException $e) {
        error_log("❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        return false;
    }
}
if (isset($_GET['debug_db'])) {
    checkDatabaseConnection();
}

define('SITE_URL', 'https://dhlil-aljanoub-web.onrender.com');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

$database = new Database();
$pdo = $database->getConnection();
?>
