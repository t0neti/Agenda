<?php

require_once('logica/conexion.php');
require_once('logica/formatearDatos.php');

// Contenido a servir
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Busco si existe un id
    $nombre = isset($_REQUEST['nombre']) && $_REQUEST['nombre'] !== "" ? $_REQUEST['nombre'] : null;

    if ($nombre) {
        $miConsulta = $miPDO->prepare('SELECT * FROM contactos WHERE estado = 1 AND nombre LIKE :nombre');

        $miConsulta->execute([
            'nombre' => "%$nombre%"
        ]);

        $datos = formatearDatosCompleta($miConsulta);

        if ($datos) {
            echo json_encode($datos);
        } else {
            echo json_encode([
                'response' => 'No se ha encontrado ningún resultado'
            ]);
        }
    } else {
        echo json_encode([
            'error' => 'Es necesario el nombre'
        ]);
    }
} else {
    echo json_encode([
        'error' => 'Metodo no permitido'
    ]);
}

