FROM php:8.3-fpm

RUN apt-get update && apt-get install -y nginx curl zip unzip git && apt-get clean

RUN docker-php-ext-install pdo_mysql mbstring gd exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan storage:link || true

RUN chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-enabled/default

EXPOSE 8080

CMD php-fpm -D && nginx -g "daemon off;"
