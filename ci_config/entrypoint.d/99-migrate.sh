#!/usr/bin/env sh

set -e

echo "Running database migrations and seeders for tenant application..."

php  /var/www/html/artisan migrate  --force
#php /var/www/html/artisan tenants:seed --force

echo "Database migrations and seeders for tenant application completed successfully."
