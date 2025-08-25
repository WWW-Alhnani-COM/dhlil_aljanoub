<?php
// دالة لتحميل الصور بأمان
function uploadImage($file, $target_dir) {
    $errors = [];
    $file_name = basename($file["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // التحقق من أن الملف صورة
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        $errors[] = "الملف ليس صورة.";
    }
    
    // التحقق من حجم الملف (5MB كحد أقصى)
    if ($file["size"] > 5000000) {
        $errors[] = "حجم الصورة كبير جداً. الحد الأقصى هو 5MB.";
    }
    
    // السماح بصيغ معينة فقط
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        $errors[] = "فقط ملفات JPG, JPEG, PNG & GIF مسموح بها.";
    }
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // إنشاء اسم فريد للملف
    $new_file_name = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;
    
    if (empty($errors)) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_file_name;
        } else {
            $errors[] = "حدث خطأ أثناء رفع الصورة.";
        }
    }
    
    return ['errors' => $errors];
}

// دالة للحماية من XSS
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// دالة للتحقق من البريد الإلكتروني
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// دالة للتحقق من رقم الهاتف
function validatePhone($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone);
}
// دالة لإنشاء كلمة مرور مشفرة
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// دالة للتحقق من قوة كلمة المرور
function isPasswordStrong($password) {
    // يجب أن تحتوي على 8 أحرف على الأقل، حرف كبير، حرف صغير، رقم، ورمز خاص
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

// دالة لإنشاء token للحماية من CSRF
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// دالة للتحقق من token الحماية من CSRF
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// دالة لتحسين أداء الاستعلامات
function optimizeQueries() {
    global $pdo;
    $pdo->query("ANALYZE;");
}
?>