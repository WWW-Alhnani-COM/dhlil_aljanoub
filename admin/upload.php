<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

checkPermission();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_result = uploadImage($_FILES['file'], UPLOAD_PATH);
        
        if (is_array($upload_result) && isset($upload_result['errors'])) {
            echo json_encode(['success' => false, 'message' => implode(', ', $upload_result['errors'])]);
        } else {
            echo json_encode(['success' => true, 'filePath' => 'uploads/' . $upload_result]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'لم يتم رفع أي ملف أو حدث خطأ أثناء الرفع']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة']);
}
?>