FROM php:8.2-apache

# Cài extension cần thiết
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Enable rewrite (Laravel cần)
RUN a2enmod rewrite

# Copy code vào container
COPY . /var/www/html/

# Set quyền
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

# Trỏ Apache vào public/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80