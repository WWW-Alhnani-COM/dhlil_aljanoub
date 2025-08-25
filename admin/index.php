<?php
require_once(__DIR__ . '/includes/config.php');
require_once '../includes/auth.php';
checkPermission();

// جلب عدد المنتجات
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
$products_count = $stmt->fetch()['count'];

// جلب أحدث المنتجات
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
$recent_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - إدارة المنتجات</title>
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
                <li><a href="#" class="active"><i class="fas fa-th-large"></i> المنتجات</a></li>
                <li><a href="#"><i class="fas fa-images"></i> معرض الأعمال</a></li>
                <li><a href="#"><i class="fas fa-star"></i> آراء العملاء</a></li>
                <li><a href="#"><i class="fas fa-envelope"></i> طلبات التواصل</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> الإعدادات</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>إدارة المنتجات</h2>
                <button class="btn-primary" onclick="openProductModal()"><i class="fas fa-plus"></i> إضافة منتج جديد</button>
            </div>

            <div id="alertBox" class="alert"></div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $products_count; ?></h3>
                        <p>إجمالي المنتجات</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>زيارات اليوم</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>طلبات جديدة</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>أحدث المنتجات</h3>
                <div class="products-grid" id="productsGrid">
                    <?php if (!empty($recent_products)): ?>
                        <?php foreach ($recent_products as $product): ?>
                        <div class="product-card" data-id="<?php echo $product['id']; ?>">
                            <div class="product-image">
                                <img src="../<?php echo $product['image_url']; ?>" alt="<?php echo $product['name_ar']; ?>">
                                <span class="product-badge"><?php echo $product['badge_text']; ?></span>
                            </div>
                            <div class="product-content">
                                <h3 class="product-title"><?php echo $product['name_ar']; ?></h3>
                                <div class="product-price"><?php echo $product['price']; ?></div>
                                <div class="product-meta">
                                    <span><?php echo $product['category']; ?></span>
                                    <span><?php echo $product['type']; ?></span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-action btn-edit" onclick="editProduct(<?php echo $product['id']; ?>)">تعديل</button>
                                    <button class="btn-action btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">حذف</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>لا توجد منتجات</p>
                    <?php endif; ?>
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
