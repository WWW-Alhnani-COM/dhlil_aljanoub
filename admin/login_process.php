<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// بدء الجلسة
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    error_log("محاولة تسجيل دخول للمستخدم: " . $username);
    
    try {
        // التحقق من بيانات المستخدم في قاعدة البيانات
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // تسجيل الدخول ناجح
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            
            error_log("تم تسجيل الدخول بنجاح للمستخدم: " . $username);
            echo json_encode(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح']);
        } else {
            // فشل تسجيل الدخول
            error_log("فشل تسجيل الدخول للمستخدم: " . $username);
            echo json_encode(['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في الخادم: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة']);
}
?>
