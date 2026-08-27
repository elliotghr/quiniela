#!/bin/bash

cd /var/www/html

echo "Installing dependencies from lock file"
composer install --no-dev --optimize-autoloader

echo "Starting Apache"
exec apache2-foreground