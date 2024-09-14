# Proyecto Laravel

## Requisitos Previos

-   PHP >= 7.3
-   Composer
-   MySQL o cualquier otra base de datos compatible
-   Node.js y npm (opcional, si se usa Laravel Mix)

## Archivo .env

Copiar el contenido del archivo .env.example y configurar la conexion a la DB

## Instalación de Dependencias

Para instalar las dependencias del proyecto, ejecuta:

```bash

Activar el servidor
php artisan serve

Instalar las dependencias
composer install

Ejecutar las migraciones
php artisan migrate

Ejecutar Seeders
php artisan db:seed

Activar verificacion de los jobs
php artisan queue:work

Servidor de envios de mail
docker run --rm -p 1025:1025 -p 8025:8025 axllent/mailpit

```

## Uso

```
1- Crear un usuario

2- Hacer login para generar token y poder autenticarse

3- Hacer uso de cualquier endpoint

4- Url de la documentacion http://localhost:8000/api/documentation#/Productos/c1ff6f862214e1896c59cfe0491ce0e8

```
