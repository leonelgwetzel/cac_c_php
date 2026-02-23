<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductoController;
use App\Views\RespuestasJSON;

# Definición de rutas
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    // Listado de productos
    $r->addRoute('GET', '/productos', [ProductoController::class, 'listadoProductos']);

    // Producto especifico
    $r->addRoute('GET', '/productos/{id:\d+}', [ProductoController::class, 'obtenerProducto']);

    // Nuevo producto
    $r->addRoute('POST', '/producto', [ProductoController::class, 'crearProducto']);

    // Editar producto
    $r->addRoute('PUT', '/producto/{id:\d+}', [ProductoController::class, 'actualizarProducto']);

    // Eliminar producto
    $r->addRoute('DELETE', '/producto/{id:\d+}', [ProductoController::class, 'borrarProducto']);
        

});

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($method, $uri);

header('Content-Type: application/json');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        RespuestasJSON::respuesta('Ruta inexistente', false,null, 404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        RespuestasJSON::respuesta('Método no permitido', false,null,405);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if (is_callable($handler)) {
            $handler($vars);
        } else {
            [$class, $method] = $handler;
            (new $class())->$method($vars);
        }
        break;
}