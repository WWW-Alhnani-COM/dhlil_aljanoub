-- إنشاء جدول المستخدمين الإداريين
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إنشاء جدول المنتجات
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name_ar VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    description_ar TEXT,
    description_en TEXT,
    category VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    price VARCHAR(100) NOT NULL,
    price_label VARCHAR(100),
    warranty VARCHAR(100),
    tags TEXT,
    image_url VARCHAR(500),
    badge_text VARCHAR(100),
    whatsapp_message TEXT,
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إنشاء جدول معرض الأعمال
CREATE TABLE IF NOT EXISTS gallery (
    id SERIAL PRIMARY KEY,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255),
    description_ar TEXT,
    description_en TEXT,
    image_url VARCHAR(500) NOT NULL,
    category VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إنشاء جدول آراء العملاء
CREATE TABLE IF NOT EXISTS testimonials (
    id SERIAL PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    client_position VARCHAR(100),
    content_ar TEXT NOT NULL,
    content_en TEXT,
    rating INTEGER DEFAULT 5,
    image_url VARCHAR(500),
    is_active BOOLEAN DEFAULT true,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إنشاء جدول طلبات التواصل
CREATE TABLE IF NOT EXISTS contact_requests (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إضافة المستخدم الإداري الافتراضي
-- كلمة المرور: admin123 (مشفرة)
INSERT INTO admin_users (username, password, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@alhnani.com', 'مدير النظام');

-- إضافة بعض المنتجات التجريبية
INSERT INTO products (name_ar, description_ar, category, type, price, price_label, warranty, tags, badge_text, whatsapp_message, is_active) VALUES 
('سواتر خشبية فاخرة', 'سواتر خشبية عالية الجودة مصنوعة من أفضل أنواع الخشب، مناسبة للفيلات والمنازل الحديثة.', 'سواتر', 'خشبية', '2500', 'تبدأ من', '3 سنوات', 'سواتر,خشب,فاخرة', 'الأكثر مبيعاً', 'مرحباً، أنا مهتم بمعرفة المزيد عن السواتر الخشبية الفاخرة', true),
('مظلات حدائق متحركة', 'مظلات حدائق يمكن فتحها وإغلاقها حسب الحاجة، مصنوعة من مواد عالية المقاومة للعوامل الجوية.', 'مظلات', 'متحركة', '1800', 'سعر التثبيت', '5 سنوات', 'مظلات,حدائق,متحركة', 'عرض خاص', 'أرغب في الحصول على عرض سعر للمظلات المتحركة', true),
('هناجر صناعية', 'هناجر صناعية قوية ومناسبة للمستودعات والمصانع، بتصميم عصري ومتانة عالية.', 'هناجر', 'صناعية', '5000', 'للمتر المربع', '10 سنوات', 'هناجر,صناعية,مستودعات', 'جديد', 'أحتاج إلى هنجر صناعي بمساحة كبيرة', true),
('سواتر حديد مخصوص', 'سواتر حديدية مصنوعة حسب الطلب، تناسب جميع المساحات وتوفر الخصوصية والأمان.', 'سواتر', 'حديد', '3200', 'سعر التصميم', '4 سنوات', 'سواتر,حديد,مخصوص', 'مخصص', 'أرغب في تصميم سور حديدي مخصص لمساحتي', true);

-- إضافة بعض الصور للمعرض
INSERT INTO gallery (title_ar, description_ar, image_url, category, is_active) VALUES 
('سواتر فيلا فاخرة', 'تنفيذ سواتر خشبية لفيلا حديثة في شمال الرياض', 'uploads/gallery/villa1.jpg', 'سواتر', true),
('مظلات منتجع', 'تركيب مظلات متحركة لمنتجع سياحي', 'uploads/gallery/resort1.jpg', 'مظلات', true),
('هنجر صناعي', 'إنشاء هنجر صناعي لمصنع ألبان', 'uploads/gallery/factory1.jpg', 'هناجر', true);

-- إضافة آراء عملاء
INSERT INTO testimonials (client_name, client_position, content_ar, rating, is_active) VALUES 
('محمد أحمد', 'مالك فيلا', 'خدمة ممتازة وجودة عالية في التنفيذ، فريق محترف وسريع في التسليم.', 5, true),
('سعيد الغامدي', 'مدير مصنع', 'هناجر قوية ومتينة، تناسب احتياجاتنا الصناعية، شكراً لفريق العمل.', 5, true),
('فاطمة العتيبي', 'ربة منزل', 'المظلات جميلة وتضفي جمالاً على حديقة منزلي، أنصح بالتعامل معهم.', 4, true);

-- إنشاء فهارس لتحسين الأداء
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category);
CREATE INDEX IF NOT EXISTS idx_products_active ON products(is_active);
CREATE INDEX IF NOT EXISTS idx_gallery_category ON gallery(category);
CREATE INDEX IF NOT EXISTS idx_testimonials_active ON testimonials(is_active);
CREATE INDEX IF NOT EXISTS idx_contact_status ON contact_requests(status);

-- تحديث تاريخ التعديل تلقائياً
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_admin_users_updated_at BEFORE UPDATE ON admin_users FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
