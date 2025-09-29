FROM php:8.2-apache

# تثبيت إضافات PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# نسخ جميع الملفات
COPY . /var/www/html/

# تفعيل mod_rewrite
RUN a2enmod rewrite

# تغيير DocumentRoot إلى المجلد الرئيسي (ليس admin)
# لأن لدينا ملفات في الجذر أيضاً (contact.php, products.php)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# إصلاح الصلاحيات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]
