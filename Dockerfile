# Dockerfile لمشروع PHP
FROM php:8.2-apache

# تفعيل PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# نسخ ملفات المشروع إلى مجلد السيرفر
COPY . /var/www/html/

# إنشاء مجلد uploads (لو غير موجود)
RUN mkdir -p /var/www/html/admin/uploads \
    && chown -R www-data:www-data /var/www/html/admin/uploads \
    && chmod -R 755 /var/www/html/admin/uploads

# فتح المنفذ 10000 (Render يستخدم هذا المنفذ)
EXPOSE 10000

# تشغيل Apache في الخلفية
CMD ["apache2-foreground"]
