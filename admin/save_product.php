<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

checkPermission();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name_ar = sanitizeInput($_POST['name_ar']);
    $description_ar = sanitizeInput($_POST['description_ar']);
    $category = sanitizeInput($_POST['category']);
    $type = sanitizeInput($_POST['type']);
    $price = sanitizeInput($_POST['price']);
    $price_label = sanitizeInput($_POST['price_label'] ?? '');
    $warranty = sanitizeInput($_POST['warranty'] ?? '');
    $tags = sanitizeInput($_POST['tags']);
    $badge_text = sanitizeInput($_POST['badge_text']);
    $whatsapp_message = sanitizeInput($_POST['whatsapp_message']);
    
    try {
        // معالجة رفع الصورة
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['image'], UPLOAD_PATH);
            
            if (is_array($upload_result) && isset($upload_result['errors'])) {
                echo json_encode(['success' => false, 'message' => implode(', ', $upload_result['errors'])]);
                exit;
            } else {
                $image_url = 'uploads/' . $upload_result;
            }
        }
        
        if ($id) {
            // تحديث منتج موجود
            if ($image_url) {
                $stmt = $pdo->prepare("UPDATE products SET name_ar = :name_ar, description_ar = :description_ar, category = :category, type = :type, price = :price, price_label = :price_label, warranty = :warranty, tags = :tags, badge_text = :badge_text, whatsapp_message = :whatsapp_message, image_url = :image_url, updated_at = NOW() WHERE id = :id");
                $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name_ar = :name_ar, description_ar = :description_ar, category = :category, type = :type, price = :price, price_label = :price_label, warranty = :warranty, tags = :tags, badge_text = :badge_text, whatsapp_message = :whatsapp_message, updated_at = NOW() WHERE id = :id");
            }
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            // إضافة منتج جديد
            $stmt = $pdo->prepare("INSERT INTO products (name_ar, description_ar, category, type, price, price_label, warranty, tags, image_url, badge_text, whatsapp_message) VALUES (:name_ar, :description_ar, :category, :type, :price, :price_label, :warranty, :tags, :image_url, :badge_text, :whatsapp_message)");
                       $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        }
        
        // ربط البارامترات
        $stmt->bindParam(':name_ar', $name_ar, PDO::PARAM_STR);
        $stmt->bindParam(':description_ar', $description_ar, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':price_label', $price_label, PDO::PARAM_STR);
        $stmt->bindParam(':warranty', $warranty, PDO::PARAM_STR);
        $stmt->bindParam(':tags', $tags, PDO::PARAM_STR);
        $stmt->bindParam(':badge_text', $badge_text, PDO::PARAM_STR);
        $stmt->bindParam(':whatsapp_message', $whatsapp_message, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حفظ المنتج']);
        }
        
    } catch (PDOException $e) {
        error_log("Save product error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام: ' . $e->getMessage()]);
    }
}
?>