<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
checkPermission();

// جلب إحصائيات المنتجات
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
$products_count = $stmt->fetch()['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE is_active = true");
$active_products = $stmt->fetch()['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE is_active = false");
$inactive_products = $stmt->fetch()['count'];

// جلب أحدث المنتجات
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
$recent_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الرئيسية</title>
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
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="index.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
                <li><a href="#"><i class="fas fa-images"></i> معرض الأعمال</a></li>
                <li><a href="#"><i class="fas fa-star"></i> آراء العملاء</a></li>
                <li><a href="#"><i class="fas fa-envelope"></i> طلبات التواصل</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> الإعدادات</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>لوحة التحكم الرئيسية</h2>
                <p>مرحباً بك في لوحة تحكم موقع ظل الجنوب</p>
            </div>

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
                        <h3><?php echo $active_products; ?></h3>
                        <p>المنتجات النشطة</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-eye-slash"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $inactive_products; ?></h3>
                        <p>المنتجات المخفية</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>زيارات اليوم</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>أحدث المنتجات المضافة</h3>
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
                                <div class="product-status">
                                    <span class="status-badge <?php echo $product['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $product['is_active'] ? 'نشط' : 'مخفي'; ?>
                                    </span>
                                </div>
                                <div class="product-actions">
                                    <button class="btn-action btn-edit" onclick="location.href='index.php?edit=<?php echo $product['id']; ?>'">تعديل</button>
                                    <button class="btn-action btn-view" onclick="window.open('../index.php#products', '_blank')">عرض</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>لا توجد منتجات مضافة بعد</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <h3>الإجراءات السريعة</h3>
                <div class="quick-actions">
                    <button class="btn-primary" onclick="location.href='index.php'">
                        <i class="fas fa-th-large"></i> إدارة المنتجات
                    </button>
                    <button class="btn-primary" onclick="openProductModal()">
                        <i class="fas fa-plus"></i> إضافة منتج جديد
                    </button>
                    <button class="btn-primary" onclick="window.open('../index.php', '_blank')">
                        <i class="fas fa-eye"></i> معاينة الموقع
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="js/script.js"></script>
</body>
</html>