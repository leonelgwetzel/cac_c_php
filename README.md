# PHP CHALLENGE - API REST Productos

API REST para Challenge técnico con PHP nativo, MySQL y Docker.

## Técnologías y requerimientos

* Docker.
* PHP 8.2 Apache (Img. docker: php:8.2-apache).
* MySQL (Img. docker: mysql:8.0)
*

## Instalación y configuración

1) Clonar el repositorio. 
```bash
git clone https://github.com/leonelgwetzel/cac_c_php.git
```
2) Renombrar el archivo [config.example.env](github.com/leonelgwetzel/cac_c_php/blobconfig.env.example/main/config.env.example) a config.env, si decide por otro nombre ajustar el nombre en el [docker-compose.yml](github.com/leonelgwetzel/cac_c_php/blobconfig.env.example/main/config.env.example)


3) Instalar dependencias de Composer:
```bash
cd api && composer install
```

4) Asegurarse que los puertos 8080 y 3307 esten disponibles, caso contrario elegir otros y configurar [docker-compose.yml](github.com/leonelgwetzel/cac_c_php/blobconfig.env.example/main/config.env.example).

5) Levantar los contenedores:
```bash
docker compose up -d --build
```
## Variable de entorno PRECIO_USD
En el archivo `.env` podés modificar el valor del precio del dolar al día de la fecha.
```
PRECIO_USD=1430
```
