#!/bin/bash

# DEFINIR LA RUTA PRINCIPAL
export CI_PROJECT_PATH=/var/www/html

# INSTALAR COMPOSER
cd "${CI_PROJECT_PATH}"
composer install --no-dev

# MODIFICAR PERMISOS DE WRITABLE
chmod 777 -R "${CI_PROJECT_PATH}/writable"

# INICIAR APACHE
apache2-foreground