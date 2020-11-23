#!/bin/sh -e

docker-compose --project-name checklist-lumen --file docker/docker-compose.yaml up --detach

cp .env.example .env

composer install

php artisan migrate
php artisan db:seed
php -S localhost:8000 -t ./public