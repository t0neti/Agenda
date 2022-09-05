<?php
//importaciones
require_once('logica/conexion.php');

session_start();

if (isset($_SESSION['usuario'])) {
    // Desactivar token del usuario en la base de datos
    $miUpdate = $miPDO->prepare('UPDATE sesiones SET estado = 0 WHERE token_sesion = :tokenSesion;');
    $miUpdate->execute([
        'tokenSesion' => $_SESSION['usuario']
    ]);

    // Destruir las sesiones del usuario
    session_destroy();
    // redirecionar a login
    header('location: login.php?cerrar-sesion=1');
}
header('location:login.php');
die();
?>

<?php require_once("componentes/head.php"); ?>
<?php require_once("componentes/header-login.php"); ?>

<main class="main bg-intro">
    <section class="intro">
        <h1> Has cerrado sesión</h1>
        <a href="login.php"> pulsa Aquí </a>
    </section>
</main>

</body>
</html>
