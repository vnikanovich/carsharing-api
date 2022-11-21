FROM php:8.1-fpm

RUN apt-get update && dpkg --configure -a && \
         apt-get install -y libpq-dev libzip-dev zlib1g-dev zip libfreetype6 libfreetype6-dev libjpeg-dev libjpeg62-turbo-dev libpng-dev lsof && \
         docker-php-ext-configure gd --with-freetype --with-jpeg && \
         docker-php-ext-install zip pdo pdo_pgsql pdo_mysql pgsql -j$(nproc) gd exif && \
         rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY --chown=www-data:www-data . .

RUN composer install
CMD php artisan cache:clear && \
    php artisan key:generate && \
    php artisan migrate && \
    php artisan storage:link && \
    php artisan db:seed && \
    php-fpm