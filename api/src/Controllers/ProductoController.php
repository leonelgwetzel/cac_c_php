<?php
namespace App\Controllers;

require_once __DIR__ . '/../Core/constants.php';

use App\Models\ProductoModel;
use App\Views\RespuestasJSON;
use App\Core\Request;

class ProductoController {
    
    private ProductoModel $modelo;

    public function __construct()
    {
        $this->modelo = new ProductoModel();
    }


    /**
     * Alta de producto
     * @return void
     */
    public function crearProducto(){

        try {

            # Valido el request y obtengo los datos parseados
            $validarRequest = Request::validarRequest(ProductoModel::$campos);

            if(count($validarRequest['errores']) > 0){
                return RespuestasJSON::respuesta($validarRequest['errores'], false,null, 400);
            }
            

            # Realizo el insert
            $datosRequest = $validarRequest['datos'];
            $idProducto = $this->modelo->crearProducto($datosRequest);

            # Si no se realizo el insert
            if ($idProducto === 0) {
                throw new \Exception('Error al insertar producto en la base de datos');
            }

            # Actualizo los datos faltantes (precio en dolares y ID obtenido)
            $productoFinal = ProductoModel::agregarPrecioUsd($datosRequest);
            $productoFinal['id'] = $idProducto;

            return RespuestasJSON::respuesta('Producto insertado correctamente', true,$productoFinal,201);code: 


        } catch (\Exception $e) {
            return RespuestasJSON::respuesta($e->getMessage(), false, null, 500);
        }

    }

    /**
     * Obtener listado completo de productos
     * 
     */
    public function listadoProductos(){

        # Obtengo los datos
        $resultado = $this->modelo->obtenerProductos();
    
        return RespuestasJSON::respuesta('Listado de productos recuperado con éxito', true, $resultado);
    }

    /**
     * Obtener información de un producto especifico
     * @param array $params (variables entregadas por fastroutes)
     * @return void
     */
    public function obtenerProducto(array $params){
        
        # Recupero el id entregado por fastroutes a los parametros y solicito el producto
        $id = $params['id'];

        $resultado = $this->modelo->obtenerProductos($id);
    
        return RespuestasJSON::respuesta('Listado de productos recuperado con éxito', true, $resultado);
    }
}