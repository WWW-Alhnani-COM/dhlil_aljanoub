FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html/

RUN a2enmod rewrite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# تغيير DocumentRoot إلى مجلد admin
RUN sed -i 's|/var/www/html|/var/www/html/admin|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/admin|g' /etc/apache2/apache2.conf

CMD ["apache2-foreground"]
