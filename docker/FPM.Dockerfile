FROM php:8.1-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN docker-php-ext-install mysqli pdo pdo_mysql

#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:2.4 /user/bin/composer /user/bin/composer

COPY ./service/composer.* ./

RUN composer install

COPY ./service .