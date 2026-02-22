<?php
namespace App\Core;

require_once __DIR__ . '/constants.php';

use PDO;
use PDOException;
use App\Views\RespuestasJSON;

class Database {

    private static ?Database $instancia = null;
    private PDO $conexion;

    private function __construct() {
        try {
            $this->conexion = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            error_log($e->getMessage());
            RespuestasJSON::respuesta('Error al conectarse a la base de datos', false,null, '500');
            exit;
        }
    }

    public static function getInstancia(): Database {
        if (self::$instancia === null) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }

    public function getConexion(): PDO {
        return $this->conexion;
    }
}