#!/bin/bash

# DEFINIR LA RUTA PRINCIPAL
export CI_PROJECT_PATH=/var/www/html

# INSTALAR COMPOSER
cd "${CI_PROJECT_PATH}"
composer install --no-dev

# INSTALAR NPM
cd "${CI_PROJECT_PATH}/public"
npm install

# MODIFICAR PERMISOS DE WRITABLE
chmod 777 -R "${CI_PROJECT_PATH}/writable"

# COMPILAR EL ARCHIVO GLOBAL SASS
sass "${CI_PROJECT_PATH}/public/css/custom-bootstrap.scss" "${CI_PROJECT_PATH}/public/css/custom-bootstrap.css"

# INICIAR APACHE EN SEGUNDO PLANO
apache2-foreground
