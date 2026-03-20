FROM composer:2 AS vendor

WORKDIR /var/www/html

COPY composer.json composer.lock ./
ARG INSTALL_DEV=false
RUN if [ "$INSTALL_DEV" = "true" ]; then \
      composer install \
      --prefer-dist \
      --no-interaction \
      --no-progress \
      --no-scripts \
      --ignore-platform-req=php \
      --ignore-platform-req=ext-bcmath \
      --optimize-autoloader; \
    else \
      composer install \
      --no-dev \
      --prefer-dist \
      --no-interaction \
      --no-progress \
      --no-scripts \
      --ignore-platform-req=php \
      --ignore-platform-req=ext-bcmath \
      --optimize-autoloader; \
    fi

COPY . .
RUN rm -f bootstrap/cache/*.php \
    && if [ "$INSTALL_DEV" = "true" ]; then \
      composer dump-autoload --optimize --no-interaction; \
    else \
      composer dump-autoload --optimize --no-dev --no-interaction --no-scripts; \
    fi


FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring bcmath exif pcntl gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /var/www/html /var/www/html
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm", "-F"]
