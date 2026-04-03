FROM php:8.2-apache

# ✅ CÀI THƯ VIỆN POSTGRES (QUAN TRỌNG NHẤT)
RUN apt-get update && apt-get install -y libpq-dev

# Cài extension
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Cài composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable rewrite
RUN a2enmod rewrite

# Copy code
COPY . /var/www/html/

WORKDIR /var/www/html

# Install Laravel
RUN composer install --no-dev --optimize-autoloader

# Set quyền
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

# Trỏ public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80