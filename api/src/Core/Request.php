<?php

namespace App\Core;

class Request {

    /**
     * Recuperar JSON enviado en la solicitud
     * @return array
     */
    public static function getBody(): array {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * Validar datos recibidos en una solicitud
     * @param array $body // Cuerpo
     * @param array $camposRequeridos // Campos con tipo de dato incluido ['nombre' => string]
     * @return string[]
     */
    public static function validarRequest($camposRequeridos,$body = null): array{

        // Si no recibo body, lo recupero
        if(!$body){
            $body = self::getBody();
        }

        $errores = [];

        // Recorro los campos requeridos para verificar su existencia
        foreach ($camposRequeridos as $campo => $tipo) {

            // Si no recibo el campo, lo sumo al error
            if( !isset($body[$campo]) || $body[$campo] === ''){
                $errores[] = "El campo '$campo' es requerido ";
                continue;
            }

            // Parseo el valor al tipo de dato requerido y valido que tenga sentido
            switch ($tipo) {
                case 'string':
                    $body[$campo] = trim((string)$body[$campo]);

                    if (strlen($body[$campo]) < 1) {
                        $errores[] = "El campo '$campo' no puede estar vacío.";
                    }
                    break;

                case 'int':
                    $valorFiltrado = filter_var($body[$campo], FILTER_VALIDATE_INT);
                    if ($valorFiltrado === false) {
                        $errores[] = "El campo '$campo' debe ser un número entero.";
                    } else {
                        $body[$campo] = $valorFiltrado;
                    }
                    break;
                    
                case 'float':
                    $valorFiltrado = filter_var($body[$campo], FILTER_VALIDATE_FLOAT);

                    if ($valorFiltrado === false) {
                        $errores[] = "El campo '$campo' debe ser un número decimal válido.";
                    } else {
                        $body[$campo] = $valorFiltrado;
                    }
                    break;
                
                default:
                    break;
            }


        }

        return ['errores' => $errores, 'datos' => $body];

    }

    /**
     * Validar request parcial
     */
    public static function validarRequestParcial($camposRequeridos,$body = null){

        # Si no se recibe body de la petición, lo recupero
        if (!$body) {
            $body = self::getBody();
        }

        $errores = [];

        # Filtro los campos para quedarme solamente con las claves que corresponden a los campos requeridos
        $camposRecibidos = array_intersect_key($camposRequeridos, $body);

        # Si no se recibieron campos requeridos notifico
        if (empty($camposRecibidos)) {
            $errores[] = "Debe enviar al menos un campo para poder actualizar el producto";
            return ['errores' => $errores, 'datos' => $body];
        }

        # Valido los campos
        return self::validarRequest($camposRecibidos, $body);
    }
}