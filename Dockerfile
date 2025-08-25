# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# إعداد المتغيرات البيئية للتوقيت
ENV TZ=Asia/Aden
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# تحديث النظام وتثبيت الحزم اللازمة لتثبيت امتدادات PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    gcc make autoconf libc-dev pkg-config \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# نسخ ملفات المشروع إلى مجلد Apache
COPY ./admin/ /var/www/html/

# منح الصلاحيات للمجلدات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# تفعيل mod_rewrite في Apache
RUN a2enmod rewrite

# تعيين منفذ الاستماع
EXPOSE 80

# تشغيل Apache في الوضع الأمامي
CMD ["apache2-foreground"]
