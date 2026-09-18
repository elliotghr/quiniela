#!/bin/bash

cd /var/www/html


echo "Installing dependencies from lock file"

composer install --no-dev --optimize-autoloader

composer require mongodb/mongodb

composer require twbs/bootstrap-icons

echo "Starting Apache"

exec apache2-foreground