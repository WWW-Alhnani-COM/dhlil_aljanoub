<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

checkPermission();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // الحصول على معلومات الصورة لحذفها
        $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch();
        
        // حذف المنتج من قاعدة البيانات
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // حذف الصورة إذا كانت موجودة
            if ($product && !empty($product['image_url']) && file_exists('../' . $product['image_url'])) {
                unlink('../' . $product['image_url']);
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حذف المنتج']);
        }
    } catch (PDOException $e) {
        error_log("Delete product error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'لم يتم تحديد معرف المنتج']);
}
?>