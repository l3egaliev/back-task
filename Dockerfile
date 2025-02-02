FROM php:8.2-apache

# Обновляем пакеты и устанавливаем нужные расширения
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
 && docker-php-ext-install pdo pdo_mysql

# Включаем модуль mod_rewrite
RUN a2enmod rewrite

# Обеспечиваем, чтобы DocumentRoot указывал на директорию public Laravel
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Копируем файлы приложения
COPY . /var/www/html

# Устанавливаем правильные права для Apache
RUN chown -R www-data:www-data /var/www/html
