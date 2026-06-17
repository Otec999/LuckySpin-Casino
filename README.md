# 🎰 LuckySpin Casino Platform

<p align="center">
  <img src="https://img.shields.io/badge/version-1.0.0-blue" alt="Version">
  <img src="https://img.shields.io/badge/Laravel-7.x-red" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-7.2%2F8.0-purple" alt="PHP">
  <img src="https://img.shields.io/badge/license-MIT-green" alt="License">
</p>

**LuckySpin Casino** — современная онлайн-казино платформа с множеством игр: Слоты, Кости (Dice), Мины (Mines), X100, X30, Crash, Coin Flip, Keno, CrazyShoot, Wheel, Jackpot и многое другое. Полноценная система платежей, реферальная программа, ежедневные бонусы, чат и административная панель.

---

## 📋 Содержание

- [🇷🇺 Русский](#-русский)
- [🇬🇧 English](#-english)
- [🇦🇿 Azərbaycanca](#-azərbaycanca)

---

## 🇷🇺 Русский

### Описание

LuckySpin Casino — это полнофункциональная платформа для онлайн-казино, построенная на Laravel 7. Платформа включает в себя широкий спектр азартных игр, систему пополнения и вывода средств, реферальную программу, чат реального времени и панель администратора.

### 🎮 Игры

| Игра | Описание |
|------|----------|
| **Слоты (Slots)** | Классические игровые автоматы от ведущих провайдеров |
| **Dice** | Игра в кости — угадай число и умножь ставку |
| **Mines** | Мины — рискни и открой безопасные клетки |
| **X100 / X30** | Молниеносные игры с множителями до x100 |
| **Crash** | Набирай множитель и забери деньги вовремя |
| **Coin Flip** | Орёл или решка |
| **Keno** | Лотерейная игра с числами |
| **CrazyShoot** | Охота за множителями |

### ⚙️ Технологии

- **Backend:** Laravel 7, PHP 7.2+/8.0
- **Frontend:** Vue.js, jQuery, Bootstrap 4, SCSS
- **База данных:** MySQL
- **Кэширование:** Redis
- **Веб-сокеты:** Socket.IO, Elephant.io
- **Платежи:** Qiwi, YooMoney и другие системы
- **Авторизация:** VK, Google, Yandex, Telegram (через Socialite)

### 🚀 Быстрый запуск

#### Windows:

```bash
setup.bat
```

#### Linux / macOS:

```bash
chmod +x setup.sh
./setup.sh
```

#### Ручная установка:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install
npm run production
```

### 🔧 Настройка

1. Отредактируйте файл `.env` — укажите данные базы данных, Redis и ключи API
2. Настройте авторизацию через VK, Google, Yandex, Telegram в `.env`
3. Настройте платежные системы
4. Запустите сервер:

```bash
php artisan serve
```

### 📧 Поддержка

По всем вопросам обращайтесь в Telegram: [@mortalsoft](https://t.me/mortalsoft)

---

## 🇬🇧 English

### Description

**LuckySpin Casino** is a full-featured online casino platform built on Laravel 7. It includes a wide range of gambling games, deposit and withdrawal systems, referral program, real-time chat, and admin panel.

### 🎮 Games

| Game | Description |
|------|-------------|
| **Slots** | Classic slot machines from top providers |
| **Dice** | Dice game — guess the number and multiply your bet |
| **Mines** | Mines — take risks and uncover safe tiles |
| **X100 / X30** | Lightning-fast games with multipliers up to x100 |
| **Crash** | Ride the multiplier and cash out in time |
| **Coin Flip** | Heads or tails |
| **Keno** | Lottery-style number game |
| **CrazyShoot** | Multiplier hunting |

### ⚙️ Tech Stack

- **Backend:** Laravel 7, PHP 7.2+/8.0
- **Frontend:** Vue.js, jQuery, Bootstrap 4, SCSS
- **Database:** MySQL
- **Cache:** Redis
- **WebSockets:** Socket.IO, Elephant.io
- **Payments:** Qiwi, YooMoney and other systems
- **Auth:** VK, Google, Yandex, Telegram (via Socialite)

### 🚀 Quick Start

#### Windows:

```bash
setup.bat
```

#### Linux / macOS:

```bash
chmod +x setup.sh
./setup.sh
```

#### Manual Setup:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install
npm run production
```

### 🔧 Configuration

1. Edit `.env` file — set database credentials, Redis and API keys
2. Configure VK, Google, Yandex, Telegram auth in `.env`
3. Configure payment systems
4. Start the server:

```bash
php artisan serve
```

### 📧 Support

Contact us on Telegram: [@mortalsoft](https://t.me/mortalsoft)

---

## 🇦🇿 Azərbaycanca

### Təsvir

**LuckySpin Casino** Laravel 7 üzərində qurulmuş tam funksional onlayn kazino platformasıdır. Platformaya geniş çeşidli qumar oyunları, depozit və çıxarış sistemi, referal proqramı, real-time söhbət və admin paneli daxildir.

### 🎮 Oyunlar

| Oyun | Təsvir |
|------|--------|
| **Slots** | Qabaqcıl provayderlərdən klassik oyun maşınları |
| **Dice** | Zər oyunu — rəqəmi tap və mərcini artır |
| **Mines** | Mina — risk et və təhlükəsiz xanaları aç |
| **X100 / X30** | x100-ə qədər çarpanlı sürətli oyunlar |
| **Crash** | Çarpanı yığ və vaxtında pulu çıxar |
| **Coin Flip** | Yazı və ya tura |
| **Keno** | Nömrələrlə lotereya oyunu |
| **CrazyShoot** | Çarpan ovu |

### ⚙️ Texnologiyalar

- **Backend:** Laravel 7, PHP 7.2+/8.0
- **Frontend:** Vue.js, jQuery, Bootstrap 4, SCSS
- **Məlumat bazası:** MySQL
- **Keş:** Redis
- **Veb-soketlər:** Socket.IO, Elephant.io
- **Ödənişlər:** Qiwi, YooMoney və digər sistemlər
- **Giriş:** VK, Google, Yandex, Telegram (Socialite vasitəsilə)

### 🚀 Sürətli başlanğıc

#### Windows:

```bash
setup.bat
```

#### Linux / macOS:

```bash
chmod +x setup.sh
./setup.sh
```

#### Əl ilə qurulum:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install
npm run production
```

### 🔧 Quraşdırma

1. `.env` faylını redaktə edin — məlumat bazası, Redis və API açarlarını qeyd edin
2. VK, Google, Yandex, Telegram girişini `.env` faylında konfiqurasiya edin
3. Ödəniş sistemlərini konfiqurasiya edin
4. Serveri işə salın:

```bash
php artisan serve
```

### 📧 Dəstək

Telegram-da əlaqə saxlayın: [@mortalsoft](https://t.me/mortalsoft)

---

## 📄 Лицензия / License

This project is licensed under the MIT License.

---

<p align="center">
  <b>LuckySpin Casino</b> — <i>Spin to Win!</i> 🍀
</p>
