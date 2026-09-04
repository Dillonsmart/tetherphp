# TetherPHP application image.
#
# php:8.5-apache rather than fpm+nginx: one container, one process manager, and
# the whole thing is legible in a page — which is the same bar the framework
# holds itself to. Swap it for fpm if you need to scale the two apart.
FROM php:8.5-apache

# Apache serves public/, not the project root. Anything above it — .env, the
# source, vendor — must not be reachable over HTTP.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN set -eux; \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf; \
    a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependencies first, so editing application code does not invalidate the layer.
# The skeleton deliberately ships no composer.lock, so this resolves fresh.
COPY composer.json ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .

RUN set -eux; \
    composer dump-autoload --optimize --no-dev; \
    # Env::loadEnv() throws when .env is missing, so the container needs one to boot
    [ -f .env ] || cp .env.example .env; \
    mkdir -p storage/logs; \
    chown -R www-data:www-data storage

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1/") === false ? 1 : 0);'
