# ms-quinieland

## Microservicios

* __ms-quinieland-web:__ Interfaz web creada con CodeIgniter 4.x.x
* __ms-quinieland-db:__ Base de Datos MySQL.
* __ms-quinieland-sync:__ Sincronizador de los resultados de la quiniela.

## Clonar repositorio

Primero que nada debemos bajar el repositorio desde github, debemos de asegurarnos de:

* Configurar las llave ssh previamente en nuestro usuario de github
* Asignar permisos de escritura al usuario github en el repositorio

```bash
# Configurar usuario de git en nuestro sistema local
git config --global user.email "tu-correo"
git config --global user.name "tu-nombre"

# Clonar el repositorio
git clone git@github.com:ramon-quiroz/quinieland.com.git
```

## Preparación del Contenedor

#### Variables de Entorno

Copiar el archivo de configuración de ejemplo y asignar las variables de entorno solicitadas:

```bash
cp codeigniter/env codeigniter/.env
vim codeigniter/.env

cp mysql-containers/development/env/env mysql-containers/development/env/.env
vim mysql-containers/development/env/.env

cp mysql-containers/development/config/my-custom.cnf-sample mysql-containers/development/config/my-custom.cnf
vim mysql-containers/development/config/my-custom.cnf

cp python/env python/.env
vim python/.env

cp composer/env composer/.env
vim composer/.env
```

#### Arrancar servicio
Finalmente levantamos el contenedor:

```bash
docker-compose --file docker-compose-dev.yml down && docker-compose --file docker-compose-dev.yml up -d --build
```

#### Compilar SASS

Si modificamos los archivos sass podemos provarlos localmente a través del siguiente comando:

```bash
docker exec -it ms-quinieland-web sass /var/www/html/codeigniter/public/css/custom-bootstrap.scss /var/www/html/codeigniter/public/css/custom-bootstrap.css
```

#### Generar Llave para .env de CodeIgniter

```bash
docker exec ms-quinieland-web
php spark key:generate
```

#### Librerias Instaladas - Node
```bash
# Entrar al contenedor
docker exec ms-quinieland-web

# Carpeta de instalación
cd codeigniter/public

# Bootstrap
npm install bootstrap@5.3
```
