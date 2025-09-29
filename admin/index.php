<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');

// إذا كان المستخدم مسجلاً، توجيه للdashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
} else {
    // إذا لم يكن مسجلاً، توجيه لصفحة login
    header("Location: login.php");
    exit;
}
?>
