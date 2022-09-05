<?php

// Variables
$hostDB = getenv('MARIADB_HOST');
$nombreDB = getenv('MARIADB_DATABASE');
$usuarioDB = getenv('MARIADB_USER');
$contrasenyaDB = getenv('MARIADB_PASSWORD');

// Conexión
$hostPDO = "mysql:host=$hostDB;dbname=$nombreDB";
$miPDO = new PDO($hostPDO, $usuarioDB, $contrasenyaDB);
