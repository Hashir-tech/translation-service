# translation-service

README: Laravel Translation Service

Setup Instructions

Prerequisites

PHP 8.1+
Laravel 10+
MySQL / PostgreSQL
Composer


Installation

git clone https://github.com/Hashir-tech/translation-service.git
cd translation-service
composer install
cp .env.example .env
php artisan key:generate

Database Setup

Update .env file with your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=translation_service
DB_USERNAME=root
DB_PASSWORD=

Run migrations

php artisan migrate

Run Command For seeding dummy data

php artisan translations:generate

Running the API

php artisan serve

The API will be available at: http://127.0.0.1:8000/api/export/en
