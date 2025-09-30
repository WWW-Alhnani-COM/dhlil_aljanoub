<?php
/**
 * includes/config.php
 * Improved configuration: use environment variables, start session early,
 * ensure uploads directory exists, and safer error handling.
 */

// Start session early so code relying on $_SESSION works
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Prefer environment variables (set these in Render service settings)
        $this->host     = getenv('DB_HOST') ?: 'dpg-d3cp662li9vc73dpir7g-a.oregon-postgres.render.com';
        $this->port     = getenv('DB_PORT') ?: '5432';
        $this->db_name  = getenv('DB_NAME') ?: 'mysql_database_ieiw';
        $this->username = getenv('DB_USER') ?: 'mysql_database_ieiw_user';
        $this->password = getenv('DB_PASS') ?: 'k11NA72ExjZwLApd8JGveGrERbUxbXVu';

        // Ensure upload path exists and is writable
        $this->ensureUploadsDirExists();
    }

    public function getConnection() {
        $this->conn = null;
        try {
            // Create PostgreSQL connection via PDO
            $this->conn = new PDO(
                sprintf('pgsql:host=%s;port=%s;dbname=%s', $this->host, $this->port, $this->db_name),
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );

            // Log success for debugging in server logs
            error_log("Database connection successful: " . $this->db_name);

        } catch(PDOException $exception) {
            // Log error
            error_log("Database connection failed: " . $exception->getMessage());

            // For admins (if logged in) show a helpful message; otherwise show generic
            if (!empty($_SESSION['admin_logged_in'])) {
                http_response_code(500);
                echo "<div style='padding:20px;background:#fff3cd;color:#856404;border:1px solid #ffeeba;border-radius:6px;margin:20px;">";
                echo "<h3>خطأ في الاتصال بقاعدة البيانات</h3>";
                echo "<p>تفاصيل: " . htmlspecialchars($exception->getMessage()) . "</p>";
                echo "</div>";
                exit;
            } else {
                // Do not expose internals to public
                error_log('DB connection error shown to visitor');
                die('عذراً، حدث خطأ في النظام. يرجى المحاولة لاحقاً.');
            }
        }
        return $this->conn;
    }

    // Test connection and basic info
    public function testConnection() {
        if (!$this->conn) {
            // attempt to connect
            $this->getConnection();
            if (!$this->conn) return false;
        }

        try {
            $stmt = $this->conn->query("SELECT 1 AS test_value, current_database() AS db_name, current_user AS db_user");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("DB test connection ok: " . ($result['db_name'] ?? 'unknown'));
            return $result;
        } catch (PDOException $e) {
            error_log("DB testConnection failed: " . $e->getMessage());
            return false;
        }
    }

    public function getDatabaseInfo() {
        if (!$this->conn) {
            $this->getConnection();
            if (!$this->conn) return false;
        }

        try {
            $info = [];
            $stmt = $this->conn->query("SELECT version() AS postgres_version, current_database() AS database_name, current_user AS username");
            $info['basic'] = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name");
            $info['tables'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $info;
        } catch (PDOException $e) {
            error_log("getDatabaseInfo failed: " . $e->getMessage());
            return false;
        }
    }

    // Ensure the uploads directory exists and is writable
    private function ensureUploadsDirExists() {
        $uploadPath = defined('UPLOAD_PATH') ? UPLOAD_PATH : __DIR__ . '/../uploads/';
        if (!is_dir($uploadPath)) {
            @mkdir($uploadPath, 0755, true);
            // set owner to www-data if possible
            if (function_exists('posix_getpwuid')) {
                @chown($uploadPath, 'www-data');
            }
        }
        // try to set perms
        @chmod($uploadPath, 0755);
    }
}

// Site settings
if (!defined('SITE_URL')) define('SITE_URL', getenv('SITE_URL') ?: 'https://dhlil-aljanoub-web.onrender.com');
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Create DB connection
$database = new Database();
$pdo = $database->getConnection();

// Optional debug endpoint
if (isset($_GET['debug_db']) && $_GET['debug_db'] === 'test') {
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

// Check for admin_users table and warn in logs (do not die on missing tables)
if (empty($_SESSION['db_checked'])) {
    try {
        $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_schema='public' AND table_name='admin_users')");
        $admin_users_exists = (bool) $stmt->fetchColumn();
        if (!$admin_users_exists) {
            error_log("Notice: admin_users table not found. Import DB schema.");
        }
        $_SESSION['db_checked'] = true;
    } catch (PDOException $e) {
        error_log("Error checking tables: " . $e->getMessage());
    }
}