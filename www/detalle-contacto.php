<?php
//Imports
require_once('logica/conexion.php');

session_start();

// Variables que hemos traído y queremos mostrar desde contactos.php
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
$nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
$telefono = isset($_REQUEST['telefono']) ? $_REQUEST['telefono'] : null;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
$notas = isset($_REQUEST['notas']) ? $_REQUEST['notas'] : "";
$picture = isset($_REQUEST['picture']) ? $_REQUEST['picture'] : "";
$isFavorite = isset($_REQUEST['is_favorite']) && $_REQUEST['is_favorite'] === "on" ? 1 : 0;

// Si no hay sesión conectada, redireccionamos al login.php
if(!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
    // Comprobamos si recibimos datos por POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // realizamos la consulta
        $miConsulta = $miPDO->prepare('SELECT * FROM contactos WHERE id = :id');

        // Ejecutamos la consulta
        $miConsulta->execute([
            'nombre' => $nombre,
            'telefono' => $telefono,
            'email' => $email,
            'notas' => $notas,
            'picture' => $picture,
            'id' => $id,
            'is_favorite' => $isFavorite

        ]);

        // lo guardamos en una array para usarlo las veces que queramos.
        $listaContactos = $miConsulta->fetchAll();
    }
?>
<?php require_once("componentes/head.php"); ?>
<main class="main main-container">
    <section class="contactos__header">
        <div class="header__login-items">
            <a href="nuevoContacto.php"><img class="header__login-img" src="/assets/img/Smiley.png"
                                             alt="avatar"></a>
            <p class="header__login-text">Contacto</p>
        </div>
    </section>
    <section>
            <!-- este campo recoge la id que vamos a modificar, arriba en variables la tenemos en un isset -->
        <div>
            <input type="hidden" name="id" value="<?= $id;?>">
            <div class="dContacto__box">
                <label for="picture">
                    <img class="dContacto__box-img  " src="<?php echo $picture; ?>">
                </label>
            </div>
                <label for="nombre">
                    <p  class="dContacto__box-info" name="nombre"><?php echo $nombre; ?></p>
                </label>
                <label for="telefono">
                   <p class=dContacto__box-info-t">Teléfono:</p>
                    <p class="dContacto__box-info" name="telefono"><?php echo $telefono; ?></p>
                </label>
                <label for="email">
                     <p class="dContacto__box-info" name="email"><?php echo $email; ?></p>
                </label>
                <label for="notas">
                    <p class="dContacto__box-info-t">Notas:</p>
                     <p class="dContacto__box-info" name="notas"><?php echo $notas; ?></p>
                </label>
            </div>
            <div class="login__button login__button-login">
                <a href="modificar.php?id=<?= $id;?>&nombre=<?= $nombre;?>&telefono=<?= $telefono;?>&email=<?= $email; ?>&notas=<?= $notas; ?>&picture=<?= $picture; ?>" class="login__button-input"> <p class="dContacto__inputs-text">Editar</p></a>
                <a href="borrar.php?id=<?= $id;?>" class="login__button-input"><p class="dContacto__inputs-text"> Eliminar </p></a>
            </div>
    </section>
</main>
<?php require_once("componentes/footer.php"); ?>

