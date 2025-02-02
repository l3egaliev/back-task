# Laravel Orders System

## Описание проекта

Этот проект представляет собой систему управления заказами на Laravel. Он позволяет создавать, редактировать, удалять и просматривать заказы с различными тарифами и графиками поставки.

## Требования
- PHP 8.2+
- Composer
- MySQL (или другая поддерживаемая база данных)
- Docker и Docker Compose (опционально)
- Node.js + NPM (для работы фронтенда, если потребуется)

## Установка

### 1. Клонирование репозитория
```sh
git clone https://github.com/your-repo/orders-system.git
cd orders-system
```

### 2. Установка зависимостей
```sh
composer install
```

### 3. Создание `.env` файла
Скопируйте `.env.example` в `.env` и настройте его:
```sh
cp .env.example .env
```

Обязательно укажите параметры подключения к БД в `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=db 
DB_PORT=3306
DB_DATABASE=meal_subscription
DB_USERNAME=root
DB_PASSWORD=rootpassword
```

### 4. Генерация ключа приложения
```sh
php artisan key:generate
```

### 5. Запуск контейнеров (если используется Docker)
```sh
docker-compose up -d
```

### 6. Запуск миграций и сидеров
```sh
docker-compose exec app php artisan migrate
```

### 7. Запуск сервера Laravel
```sh
php artisan serve
```
Откройте [http://127.0.0.1:8000](http://127.0.0.1:8000) в браузере.

## Работа с заказами
- **Создание заказа**: `POST /orders`
- **Просмотр заказов**: `GET /orders`
- **Просмотр конкретного заказа**: `GET /orders/{id}`

## Очистка кеша и сессий
```sh
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Дополнительные команды (для Docker)

- Перезапустить контейнеры:
```sh
docker-compose restart
```
- Остановить контейнеры:
```sh
docker-compose down
```

## Автор
**Rakhim** - [GitHub Profile](https://github.com/l3egaliev)

