# PHP CHALLENGE - API REST Productos

API REST para Challenge técnico con PHP nativo, MySQL y Docker.

## Tecnologías
* PHP 8.2 Apache (Img. docker: php:8.2-apache)
* MySQL (Img. docker: mysql:8.0)
* Enrutador [FastRoute](https://github.com/nikic/FastRoute)

## Requerimientos
* Docker
* Composer

## Instalación y configuración

1) Clonar el repositorio. 
```bash
git clone https://github.com/leonelgwetzel/cac_c_php.git
```
2) Renombrar el archivo config.example.env a config.env, si decide por otro nombre ajustar el nombre en el docker-compose.yml


3) Instalar dependencias de Composer:
```bash
cd api && composer install
```

4) Asegurarse que los puertos 8080 y 3307 esten disponibles, caso contrario elegir otros y configurar docker-compose.yml

5) Levantar los contenedores:
```bash
docker compose up -d --build
```
## Variable de entorno PRECIO_USD
En el archivo `config.env` podés modificar el valor del precio del dolar al día de la fecha.
```
PRECIO_USD=1430
```

## Endpoints

* URL Por defecto (si no se cambió el puerto) : ```http://localhost:8080/```
* [GET] - Listado de productos: ```/productos```
* [GET] - Obtener producto: ```/productos/{id}```
* [POST] - Crear producto: ```/producto```
* [PUT] - Actualizar producto: ```/producto/{id}```
* [DELETE] - Eliminar producto: ```/producto/{id}```


### Ejemplo creación de producto [POST]
**Body:**
```json
{
    "nombre": "Producto",
    "descripcion": "Descripción",
    "precio": 1000.00 
}
```
**Campos:**
- `nombre`: String, requerido
- `descripcion`: String, requerido
- `precio`: Float, requerido


### Ejemplo actualización de producto [PUT]
> Nota:  Se pueden actualizar los campos que se deseen, no es necesario enviar todos 
**Body:**
```json
{
    "nombre": "Producto",
    "descripcion": "Descripción",
    "precio": 1000.00 
}
```
## FRONTEND

El frontend es una interfaz simple incluida para consumir la API, por cuestión de practicidad y para evitar inconvenientes con los puertos fue implementado en el contenedor de la misma API.

### Acceso
Con los contenedores levantados, ingresá desde el navegador a:

- Ruta: `http://localhost:8080/frontend/index.html`

> Nota: Si el puerto fue modificado en `docker-compose.yml`, reemplazá `8080` por el puerto configurado.

