FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html/

RUN a2enmod rewrite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# إنشاء Virtual Host لتوجيه كل شيء إلى مجلد admin
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/admin\n\
    <Directory "/var/www/html/admin">\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
