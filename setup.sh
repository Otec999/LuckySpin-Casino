#!/bin/bash

echo "======================================"
echo "  УСТАНОВКА И НАСТРОЙКА ПРОЕКТА"
echo "======================================"
echo ""

# Проверяем наличие PHP
if ! command -v php &> /dev/null; then
    echo "[ОШИБКА] PHP не найден. Установите PHP 7.4+."
    echo "apt install php7.4-cli php7.4-mysql php7.4-mbstring php7.4-xml php7.4-curl"
    exit 1
fi

# Проверяем наличие Composer
if ! command -v composer &> /dev/null; then
    echo "[ОШИБКА] Composer не найден."
    echo "Установка: curl -sS https://getcomposer.org/installer | php"
    exit 1
fi

echo "[1/6] Установка PHP-зависимостей..."
composer install --no-interaction --no-dev 2>/dev/null || echo "[ПРЕДУПРЕЖДЕНИЕ] Ошибка composer install"

echo ""
echo "[2/6] Настройка .env..."
if [ ! -f .env ]; then
    cat > .env << 'ENVEOF'
APP_NAME=LuckyCasino
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=casino
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# VK Configuration
VK_SECRET=your_vk_secret
VK_GROUP_ID=your_group_id
VK_CONFIRMATION_TOKEN=your_confirmation_token
VK_ACCESS_TOKEN=your_vk_access_token
TG_BOT_TOKEN=your_telegram_bot_token
ENVEOF
    echo "[OK] .env создан"
else
    echo "[OK] .env уже существует"
fi

echo ""
echo "[3/6] Генерация ключа приложения..."
php artisan key:generate --force 2>/dev/null || echo "[ПРЕДУПРЕЖДЕНИЕ] Ошибка генерации ключа"

echo ""
echo "[4/6] Запуск миграций..."
php artisan migrate --force 2>/dev/null || echo "[ПРЕДУПРЕЖДЕНИЕ] Ошибка миграции. Проверьте БД в .env"

echo ""
echo "[5/6] Кэширование..."
php artisan config:cache 2>/dev/null
php artisan route:cache 2>/dev/null
php artisan view:cache 2>/dev/null

echo ""
echo "[6/6] Установка JS-зависимостей..."
if command -v npm &> /dev/null; then
    npm install --silent 2>/dev/null
    npm run production 2>/dev/null
fi

echo ""
echo "======================================"
echo "  УСТАНОВКА ЗАВЕРШЕНА!"
echo "======================================"
echo ""
echo "Для запуска: php artisan serve"
echo ""
