<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = sanitizeInput($_POST['password'] ?? '');
    
    try {
        // البحث عن المستخدم في قاعدة البيانات
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username AND is_active = true");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // تسجيل الدخول ناجح
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            echo json_encode(['success' => true]);
        } else {
            // فشل تسجيل الدخول
            echo json_encode(['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام. يرجى المحاولة لاحقاً.']);
    }
}
?>