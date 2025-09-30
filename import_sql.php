<?php
// import_sql.php
// Upload a .sql file (field name "sqlfile") via POST and call with ?token=YOURTOKEN
// IMPORTANT: set environment variable IMPORT_TOKEN on Render to the same token
if (session_status() === PHP_SESSION_NONE) session_start();
$token = $_GET['token'] ?? '';
if (!$token || $token !== getenv('IMPORT_TOKEN')) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Forbidden']);
    exit;
}
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_NAME') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';
try {
    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $db);
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'DB connection failed: '.$e->getMessage()]);
    exit;
}
if (isset($_FILES['sqlfile']) && $_FILES['sqlfile']['error'] === UPLOAD_ERR_OK) {
    $sql = file_get_contents($_FILES['sqlfile']['tmp_name']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'No SQL file provided']);
    exit;
}
// Naive split by semicolon+newline. Good for most schema dumps; may fail with functions.
$parts = preg_split('/;\s*\n/', $sql);
$results = ['executed' => 0, 'errors' => []];
foreach ($parts as $part) {
    $stmt = trim($part);
    if ($stmt === '') continue;
    try {
        $pdo->exec($stmt);
        $results['executed']++;
    } catch (Exception $e) {
        $results['errors'][] = ['sql_preview' => substr($stmt,0,200), 'error' => $e->getMessage()];
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>