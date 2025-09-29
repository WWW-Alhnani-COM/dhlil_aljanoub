<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// بدء الجلسة
session_start();

// تأكد أن الطلب POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // تعيين رأس JSON
    header('Content-Type: application/json; charset=utf-8');
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
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
            
            echo json_encode(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح']);
        } else {
            // فشل تسجيل الدخول
            echo json_encode(['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
        }
    } catch (PDOException $e) {
        // في حالة خطأ، ارجع JSON وليس HTML
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في الخادم: ' . $e->getMessage()]);
    }
} else {
    // إذا لم يكن POST، ارجع خطأ JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة']);
}
?>
