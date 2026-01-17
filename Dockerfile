# Dockerfile
FROM php:8.2-fpm

# Installer dépendances système utiles à Symfony et ses bundles
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zlib1g-dev curl \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && docker-php-ext-enable opcache

# Config OPcache (perfs Symfony)
RUN echo "opcache.enable=1\n\
    opcache.enable_cli=1\n\
    opcache.memory_consumption=256\n\
    opcache.interned_strings_buffer=16\n\
    opcache.max_accelerated_files=20000\n\
    opcache.validate_timestamps=1\n\
    opcache.revalidate_freq=2" > /usr/local/etc/php/conf.d/opcache.ini

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Travailler dans le dossier projet
WORKDIR /var/www/html
