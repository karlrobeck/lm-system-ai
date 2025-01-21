FROM php:8.2-alpine3.20

WORKDIR /var/www/

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www
USER www-data

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD ["sh", "-c","php artisan migrate && php artisan serve --host=0.0.0.0 --port=8000"]