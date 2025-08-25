<?php
require_once '../includes/config.php';

session_start();
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header('Location: login.php');
exit;
?>