#!/usr/bin/env sh

set -e

echo "Running database migrations and seeders for tenant application..."

php  /var/www/html/artisan migrate --seed --force
#php /var/www/html/artisan tenants:seed --force

echo "Database migrations and seeders for tenant application completed successfully."
