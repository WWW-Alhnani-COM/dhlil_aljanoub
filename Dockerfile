FROM php:8.2-apache

# تثبيت إضافات PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# نسخ جميع الملفات
COPY . /var/www/html/

# تفعيل mod_rewrite لـ Apache
RUN a2enmod rewrite

# إصلاح الصلاحيات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads

# استخدام المنفذ الصحيح
EXPOSE 80

# الأمر الافتراضي لـ Apache
CMD ["apache2-foreground"]
