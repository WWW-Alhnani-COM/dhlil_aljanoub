FROM php:8.2-apache

# تثبيت إضافات PHP المطلوبة لـ PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# نسخ ملفات المشروع
COPY . /var/www/html/

# إعدادات Apache
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

# إنشاء مجلد uploads وإعطاء الصلاحيات
RUN mkdir -p /var/www/html/uploads && \
    chmod 755 /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads

EXPOSE 8080
