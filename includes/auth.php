<!-- auth.php --><?php
session_start();

// التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// إعادة التوجيه إذا لم يكن المستخدم مسجلاً
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

// التحقق من الصلاحيات
function checkPermission() {
    requireLogin();
}
?>