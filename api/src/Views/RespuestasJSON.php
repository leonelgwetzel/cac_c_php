<?php
namespace App\Views;

class RespuestasJSON {
    
    /**
     * Respuesta exitosa
     * @param object $data
     * @param int $code
     * @return void
     */
    public static function respuesta($mensaje, bool $estado = true, $datos = null, int $code = 200): void {

        # Seteo el codigo de respuesta de la solicitud
        http_response_code($code);

        # Armo la respuesta
        $respuesta = [
            "resultado" => $estado,
            'mensaje' => $mensaje,
        ];

        # Si hay datos a incluir lo sumo en el array
        if($datos){
            $respuesta['datos'] = $datos;
        }

        echo json_encode($respuesta);
        exit;
    }
}