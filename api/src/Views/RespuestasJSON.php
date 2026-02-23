<?php
namespace App\Views;

class RespuestasJSON {
    
    /**
     * Respuesta exitosa
     * @param object $data
     * @param int $code
     * @return void
     */
    public static function respuesta($detalle, bool $estado = true, $datos = null, int $code = 200): void {

        # Seteo el codigo de respuesta de la solicitud
        http_response_code($code);

        # Armo la respuesta
        $respuesta = [
            "resultado" => $estado
        ];

        # Manejo la clave del detalle segun el estado de la operación
        if($estado){
            $respuesta['mensaje'] = $detalle;
        }else{
            $respuesta['errores'] = $detalle;
        }
    
        # Si hay datos a incluir lo sumo en el array
        if ($datos !== null) {
            $respuesta['datos'] = $datos;
        }

        echo json_encode($respuesta);
        exit;
    }
}