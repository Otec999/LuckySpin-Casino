@echo off
chcp 65001 >nul
echo ======================================
echo   УСТАНОВКА И НАСТРОЙКА ПРОЕКТА
echo ======================================
echo.

REM Проверяем наличие PHP
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ОШИБКА] PHP не найден. Установите PHP 7.4+ и добавьте в PATH.
    echo Скачать: https://windows.php.net/download/
    pause
    exit /b 1
)

REM Проверяем наличие Composer
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ОШИБКА] Composer не найден.
    echo Скачать: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [1/6] Установка PHP-зависимостей (composer install)...
call composer install --no-interaction
if %ERRORLEVEL% NEQ 0 (
    echo [ПРЕДУПРЕЖДЕНИЕ] Ошибка при composer install. Продолжаем...
)

echo.
echo [2/6] Создание .env файла...
if not exist .env (
    copy .env.example .env >nul 2>nul
)
if not exist .env (
    echo APP_NAME=LuckyCasino > .env
    echo APP_ENV=production >> .env
    echo APP_KEY= >> .env
    echo APP_DEBUG=false >> .env
    echo APP_URL=http://localhost >> .env
    echo. >> .env
    echo DB_CONNECTION=mysql >> .env
    echo DB_HOST=127.0.0.1 >> .env
    echo DB_PORT=3306 >> .env
    echo DB_DATABASE=casino >> .env
    echo DB_USERNAME=root >> .env
    echo DB_PASSWORD= >> .env
    echo. >> .env
    echo BROADCAST_DRIVER=log >> .env
    echo CACHE_DRIVER=redis >> .env
    echo QUEUE_CONNECTION=sync >> .env
    echo SESSION_DRIVER=file >> .env
    echo SESSION_LIFETIME=120 >> .env
    echo. >> .env
    echo REDIS_HOST=127.0.0.1 >> .env
    echo REDIS_PASSWORD=null >> .env
    echo REDIS_PORT=6379 >> .env
    echo. >> .env
    echo MAIL_DRIVER=smtp >> .env
    echo MAIL_HOST=smtp.mailtrap.io >> .env
    echo MAIL_PORT=2525 >> .env
    echo MAIL_USERNAME=null >> .env
    echo MAIL_PASSWORD=null >> .env
    echo MAIL_ENCRYPTION=null >> .env
    echo. >> .env
    echo # VK Configuration >> .env
    echo VK_SECRET=your_vk_secret >> .env
    echo VK_GROUP_ID=your_group_id >> .env
    echo VK_CONFIRMATION_TOKEN=your_confirmation_token >> .env
    echo VK_ACCESS_TOKEN=your_vk_access_token >> .env
    echo TG_BOT_TOKEN=your_telegram_bot_token >> .env
    echo [OK] .env создан
) else (
    echo [OK] .env уже существует
)

echo.
echo [3/6] Генерация ключа приложения...
php artisan key:generate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ПРЕДУПРЕЖДЕНИЕ] Не удалось сгенерировать ключ
)

echo.
echo [4/6] Запуск миграций базы данных...
php artisan migrate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ПРЕДУПРЕЖДЕНИЕ] Ошибка миграции. Проверьте настройки БД в .env
)

echo.
echo [5/6] Кэширование конфигурации...
php artisan config:cache 2>nul
php artisan route:cache 2>nul
php artisan view:cache 2>nul

echo.
echo [6/6] Установка JavaScript-зависимостей...
where npm >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    call npm install --silent 2>nul
    call npm run production 2>nul
)

echo.
echo ======================================
echo   УСТАНОВКА ЗАВЕРШЕНА!
echo ======================================
echo.
echo После настройки:
echo   1. Отредактируйте .env - укажите данные БД и ключи
echo   2. Запустите: php artisan serve
echo   3. Откройте http://localhost:8000
echo.
pause
