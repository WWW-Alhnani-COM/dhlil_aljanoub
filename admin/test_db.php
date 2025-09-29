<?php
require_once '../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // اختبار الاتصال
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // اختبار وجود الجداول
    $tables = [];
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'success' => true,
        'message' => '✅ الاتصال بقاعدة البيانات ناجح',
        'tables' => $tables,
        'database' => 'mysql_database_ieiw'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '❌ فشل الاتصال بقاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>
