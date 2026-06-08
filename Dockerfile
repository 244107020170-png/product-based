FROM dunglas/frankenphp:php8.3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions \
    intl \
    zip \
    pdo_mysql \
    mbstring \
    bcmath \
    exif \
    gd

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install
RUN npm run build

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]