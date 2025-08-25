<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
checkPermission();

// جلب جميع المنتجات
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();

// البحث والتصفية
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (name_ar ILIKE :search OR tags ILIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($category) && $category !== 'all') {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

if (!empty($status) && $status !== 'all') {
    $is_active = $status === 'active' ? 'true' : 'false';
    $query .= " AND is_active = $is_active";
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$filtered_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - جميع المنتجات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="admin-header">
        <h1>لوحة تحكم ظل الجنوب</h1>
        <button class="logout-btn" onclick="logout()">تسجيل الخروج <i class="fas fa-sign-out-alt"></i></button>
    </header>

    <div class="admin-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="products.php" class="active"><i class="fas fa-th-large"></i> جميع المنتجات</a></li>
                <li><a href="index.php"><i class="fas fa-plus"></i> إضافة منتج</a></li>
                <li><a href="#"><i class="fas fa-images"></i> معرض الأعمال</a></li>
                <li><a href="#"><i class="fas fa-star"></i> آراء العملاء</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> الإعدادات</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>جميع المنتجات</h2>
                <button class="btn-primary" onclick="openProductModal()"><i class="fas fa-plus"></i> إضافة منتج جديد</button>
            </div>

            <div class="card">
                <h3>تصفية المنتجات</h3>
                <form method="GET" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="search" placeholder="بحث بالاسم أو الوسوم" value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="form-group">
                            <select name="category">
                                <option value="all">جميع الفئات</option>
                                <option value="curtain" <?php echo $category === 'curtain' ? 'selected' : ''; ?>>سواتر</option>
                                <option value="canopy" <?php echo $category === 'canopy' ? 'selected' : ''; ?>>مظلات</option>
                                <option value="hanger" <?php echo $category === 'hanger' ? 'selected' : ''; ?>>هناجر</option>
                                <option value="other" <?php echo $category === 'other' ? 'selected' : ''; ?>>أخرى</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="status">
                                <option value="all">جميع الحالات</option>
                                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>نشط</option>
                                <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>مخفي</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-action btn-edit">تصفية</button>
                        <a href="products.php" class="btn-action btn-delete">إعادة تعيين</a>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>المنتجات (<?php echo count($filtered_products); ?>)</h3>
                <div class="table-responsive">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>الفئة</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($filtered_products)): ?>
                                <?php foreach ($filtered_products as $product): ?>
                                <tr>
                                    <td>
                                        <img src="../<?php echo $product['image_url']; ?>" alt="<?php echo $product['name_ar']; ?>" class="table-image">
                                    </td>
                                    <td><?php echo $product['name_ar']; ?></td>
                                    <td><?php echo $product['category']; ?></td>
                                    <td><?php echo $product['price']; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $product['is_active'] ? 'active' : 'inactive'; ?>">
                                            <?php echo $product['is_active'] ? 'نشط' : 'مخفي'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($product['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-edit" onclick="editProduct(<?php echo $product['id']; ?>)">تعديل</button>
                                            <button class="btn-action btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">حذف</button>
                                            <button class="btn-action btn-view" onclick="window.open('../index.php#products', '_blank')">عرض</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px;">
                                        لا توجد منتجات تطابق معايير البحث
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal لإضافة/تعديل المنتج -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">إضافة منتج جديد</h2>
            
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="id" value="">
                
                <div class="form-group">
                    <label for="name_ar">اسم المنتج</label>
                    <input type="text" id="name_ar" name="name_ar" required>
                </div>
                
                <div class="form-group">
                    <label for="description_ar">وصف المنتج</label>
                    <textarea id="description_ar" name="description_ar"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category">الفئة</label>
                    <select id="category" name="category" required>
                        <option value="curtain">سواتر</option>
                        <option value="canopy">مظلات</option>
                        <option value="hanger">هناجر</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="type">النوع</label>
                    <input type="text" id="type" name="type" required>
                </div>
                
                <div class="form-group">
                    <label for="price">السعر</label>
                    <input type="text" id="price" name="price" required>
                </div>
                
                <div class="form-group">
                    <label for="price_label">تسمية السعر (اختياري)</label>
                    <input type="text" id="price_label" name="price_label">
                </div>
                
                <div class="form-group">
                    <label for="warranty">الضمان (اختياري)</label>
                    <input type="text" id="warranty" name="warranty">
                </div>
                
                <div class="form-group">
                    <label for="tags">الوسوم (مفصولة بفواصل)</label>
                    <input type="text" id="tags" name="tags" required>
                </div>
                
                <div class="form-group">
                    <label for="badge_text">نص الشارة</label>
                    <input type="text" id="badge_text" name="badge_text" required>
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_message">رسالة الواتساب</label>
                    <textarea id="whatsapp_message" name="whatsapp_message" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">صورة المنتج</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div id="imagePreview" style="margin-top: 10px; display: none;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px;">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-action btn-delete" onclick="closeModal()">إلغاء</button>
                    <button type="submit" class="btn-action btn-edit">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>