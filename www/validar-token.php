<?php
// Imports
require_once('logica/conexion.php');

// Variables
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

// Comprueba si hay un usuario que el correo y el token cuando pinchamos el correo
// Prepara INSERT
$miConsulta = $miPDO->prepare('SELECT COUNT(*) as cantidad FROM usuario WHERE email = :email AND token = :token');
// Ejecuta el nuevo registro en la base de datos
$miConsulta->execute([
    'email' => $email,
    'token' => $token
]);

$resultado = $miConsulta->fetch();

// Sii es superior a 0 la cantidad validaremos email_validado de 0 a 1.
if ((int) $resultado["cantidad"] > 0) {
    // Si existe, actualizamos email_validado a true. Informamos del exito.
    echo '<h1>Cuenta validada</h1>';
    echo '<p href="login.php">Bienvenido! - pulsa <a href="login.php"> AQUÍ </a> para Log in!</p>';
    // aquí validamos y modificamos de email_Validado de 0 a 1 ( TRUE)
    $miUpdate = $miPDO->prepare('UPDATE usuario SET email_validado = TRUE WHERE email = :email AND token = :token');
    // Ejecutamos el update
    $miUpdate->execute([
        'email' => $email,
        'token' => $token
    ]);
} else {
    // No existe, informamos.
    echo '<h1>Ups! Ha ocurrido un problema</h1>';
}
?>
<?php require_once ("componentes/head.php") ?>
</body>
</html>
