# انتخاب تصویر PHP 8.3
FROM php:8.3-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql opcache

# نصب ابزارها و افزونه‌های مورد نیاز
RUN apk add --no-cache \
	bash \
    libzip-dev \
	zip \
	unzip \
	curl \
	git \
	freetype-dev \
	libjpeg-turbo-dev \
	libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install gd zip

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کاری
WORKDIR /var/www/html

# کپی پروژه به داخل کانتینر
COPY . .

# نصب وابستگی‌ها
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

# تنظیم دسترسی‌ها
RUN chown -R www-data:www-data /var/www/html /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/storage

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "-port=8000"]