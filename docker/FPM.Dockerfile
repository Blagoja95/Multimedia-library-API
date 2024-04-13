FROM php:8.1-fpm-alpine as php_fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    install-php-extensions pdo pdo_mysql;

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY ./service/ ./

RUN composer install

RUN composer dump-autoload --optimize

FROM php_fpm as php_fpm_dev

ENV XDEBUG_MODE=off

COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN set -eux; \
    install-php-extensions xdebug;