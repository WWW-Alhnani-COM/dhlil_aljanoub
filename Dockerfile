FROM php:8.2-apache

# تثبيت إضافات PHP المطلوبة
RUN docker-php-ext-install pdo pdo_pgsql

# نسخ ملفات المشروع
COPY . /var/www/html/

# إعدادات Apache
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
