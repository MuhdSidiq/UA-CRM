#!/bin/bash
set -e

echo "[$(date)] Starting deployment script..."

# Wait for MySQL to be ready
echo "[$(date)] Waiting for MySQL..."
max_tries=30
count=0
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    sleep 2
    count=$((count+1))
    if [ $count -gt $max_tries ]; then
        echo "Failed to connect to MySQL after 60 seconds"
        exit 1
    fi
done

echo "[$(date)] Running migrations..."
php artisan migrate --force || {
    echo "Migration failed!"
    exit 1
}

# Start Apache in foreground
echo "[$(date)] Starting Apache..."
apache2-foreground
