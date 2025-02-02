FROM php:8.2-apache as base

# Устанавливаем зависимости и расширения PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
 && docker-php-ext-install pdo pdo_mysql zip

# Включаем модуль mod_rewrite
RUN a2enmod rewrite

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы приложения в контейнер (включая artisan)
COPY . /var/www/html

# Устанавливаем зависимости с Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Устанавливаем правильные права для Apache
RUN chown -R www-data:www-data /var/www/html

# Обновляем конфигурацию Apache для использования public директории Laravel
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

EXPOSE 80