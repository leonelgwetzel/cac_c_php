<?php
namespace App\Models;

use PDO;
use App\Core\Database;

class ProductoModel {

    private $pdo;

    // Schema del modelo
    public static array $campos = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'precio' => 'float'
    ];

    public function __construct() {

        // Instancio la conexión a la db
        $this->pdo = Database::getInstancia()->getConexion();
    }

    /**
     * Insertar nuevo producto
     * @param array $datos
     * @return int
     */
    public function crearProducto($datos):int {

        $query = $this->pdo->prepare('
            INSERT INTO productos (nombre, descripcion, precio) 
            VALUES (:nombre, :descripcion, :precio)
        ');

        $query->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':precio' => $datos['precio']
        ]);

        # Retorno el id insertado
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Recuperar todos los productos o un producto especifico
     * @param int/null id
     */
    public function obtenerProductos($id = null): array{

        if ($id) {
            $query = $this->pdo->prepare("
                SELECT *
                FROM productos 
                WHERE id = :id
            ");

            # Ejecuto la query y traigo el resultado
            $query->execute([':id' => $id]);
            $producto = $query->fetch();

            # Si el producto no existe, retorno la excepción
            if (!$producto) {
                throw new \Exception('Producto no encontrado');
            }

            # Agrego el precio en dolares
            return self::agregarPrecioUsd($producto);
        }

        # Ejecuto la query y traigo todos los resultados
        $query = $this->pdo->query("
            SELECT * 
            FROM productos
        ");
        $productos = $query->fetchAll();

        # Retorno los productos con el precio en dolares incluido
        return array_map([self::class, 'agregarPrecioUsd'], $productos);
    }

    
    /**
     * Baorrar un producto
     * @param int $id
     * @return bool
     */
    public function borrarProducto($id):bool {

        $query = $this->pdo->prepare('
            DELETE from productos WHERE id = :id
        ');

        $resultado = $query->execute([
            ':id' => $id
        ]);

        return $resultado;
    }
    
    /**
     * Actualizar un producto
     * @param int $id
     * @param array $datos
     * @return array
     */
    public function actualizarProducto($id,$datos): array    {

        # Obtengo los campos a actualizar y genero el string del set para la query
        $campos = array_keys($datos);
        $set = [];
        foreach ($campos as $campo) {
            $set[] = "$campo = :$campo";
        }
        $set = implode(', ', $set);
        
        $query = $this->pdo->prepare("UPDATE productos SET $set WHERE id = :id");
        $datos[':id'] = $id;

        $resultado = $query->execute($datos);

        if (!$resultado) {
            throw new \Exception('Error al actualizar producto en la base de datos');
        }

        # Obtengo el producto actualizado
        $producto = self::obtenerProductos($id);

        # Agrego el precio en dolares
        return self::agregarPrecioUsd($producto);
    }


    // FUNCIONES AUXILIARES

    /**
     * Agregar precio en dolares al producto indicado haciendo uso de la constante que posee el valor del dolar
     * @param array $producto
     * @return array
     */
    public static function agregarPrecioUsd(array $producto): array {
        
        $producto['precio_usd'] = round($producto['precio'] / PRECIO_USD, 2);

        return $producto;
    }

}

