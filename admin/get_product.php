<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

checkPermission();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch();
        
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'المنتج غير موجود']);
        }
    } catch (PDOException $e) {
        error_log("Get product error: " . $e->getMessage());
        echo json_encode(['error' => 'حدث خطأ في النظام']);
    }
} else {
    echo json_encode(['error' => 'لم يتم تحديد معرف المنتج']);
}
?>