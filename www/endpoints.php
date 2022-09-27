<?php
// Contenido a servir
header('Content-Type: application/json');
// Metodos disponibles
header('Access-Control-Allow-Methods: GET, OPTIONS');

// Varialbes
$url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$opciones = [
    'Funciones del api' => [
        'home' => [
            'endpoint' => $url,
            'metodo' => 'GET',
            'función' => 'Ver todas las funciones disponibles',
            'estado' => 'on'
        ],
        'contactos' => [
            'obtener contactos' => [
                'endpoint' => $url . 'contactos/',
                'metodo' => 'GET',
                'función' => 'Nos da la lista de contactos disponibles',
                'estado' => 'on'
            ],
            'buscar contactos' => [
                'endpoint' => $url . 'contactos/buscar/?nombre=(nombre del contacto)',
                'metodo' => 'GET',
                'parametros' => [
                    'nombre' => 'nombre del contacto',
                ],
                'función' => 'Buscamos un contacto',
                'estado' => 'on'
            ],
         ]
    ]
];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Cargo el código de respuesta
    http_response_code(200);

    // Muestro las opciones disponibles
    echo json_encode($opciones);
} else {
    // Cargo el código de respuesta
    http_response_code(405);

    // Imprimimos un JSON de respuesta.
    echo json_encode([
        'Error' => 'Metodo no permitido, debe ser un GET'
    ]);
}