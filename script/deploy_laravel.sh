#!/bin/bash

# Enter html directory
cd /var/www/html/insightspro_api

# Create cache and chmod folders
mkdir -p /var/www/html/insightspro_api/bootstrap/cache
mkdir -p /var/www/html/insightspro_api/storage/framework/sessions
mkdir -p /var/www/html/insightspro_api/storage/framework/views
mkdir -p /var/www/html/insightspro_api/storage/framework/cache
mkdir -p /var/www/html/insightspro_api/public/files/

# Install dependencies
export COMPOSER_ALLOW_SUPERUSER=1
composer install -d /var/www/html/insightspro_api/

# Copy configuration from /var/www/.env, see README.MD for more information
cp /var/www/.env /var/www/html/insightspro_api/.env

# Migrate all tables
php /var/www/html/insightspro_api/artisan migrate

# Clear any previous cached views
php /var/www/html/insightspro_api/artisan config:clear
php /var/www/html/insightspro_api/artisan cache:clear
php /var/www/html/insightspro_api/artisan view:clear

# Optimize the application
php /var/www/html/insightspro_api/artisan config:cache
php /var/www/html/insightspro_api/artisan optimize
#php /var/www/html/insightspro_api/artisan route:cache

# Change rights
chmod 777 -R /var/www/html/insightspro_api/bootstrap/cache
chmod 777 -R /var/www/html/insightspro_api/storage
chmod 777 -R /var/www/html/insightspro_api/public/files/

# Bring up application
php /var/www/html/insightspro_api/artisan up
