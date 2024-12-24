#!/bin/bash

# Run migrations
php artisan migrate --force

# Start nginx and php-fpm
service nginx start
php-fpm
