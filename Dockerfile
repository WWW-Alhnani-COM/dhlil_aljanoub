FROM php:8.2-apache

# تثبيت إضافات PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# نسخ الملفات
COPY . /var/www/html/

# تفعيل mod_rewrite
RUN a2enmod rewrite

# تغيير DocumentRoot إلى مجلد admin
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/admin|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/admin|g' /etc/apache2/conf-available/docker-php.conf

# إصلاح الصلاحيات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]
