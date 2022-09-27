<?php
function formatearDatosCompleta(object $consulta): array
{
    // Hacemos el map y entregamos el resultado
    return array_map(function ($item) {
         // Hacemos el filter
        return array_filter($item, function($valor, $clave) {
        // Comprobamos que tipo de clave tiene
            if ($clave !== intval($clave) && $clave !== 'estado') {
                // Devolvemos el dato con una clave valida
                return $valor;
            }
        }, ARRAY_FILTER_USE_BOTH);
    }, $consulta->fetchAll());
}