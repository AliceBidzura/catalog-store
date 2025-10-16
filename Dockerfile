FROM php:8.2-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev libpng-dev libjpeg-dev libfreetype6-dev

# php extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
