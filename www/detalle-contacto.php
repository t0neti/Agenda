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
            'id' => $id
        ]);

        // lo guardamos en una array para usarlo las veces que queramos.
        $listaContactos = $miConsulta->fetchAll();
    }
?>
<?php require_once("componentes/head.php"); ?>
<main class="main main-container">
    <section class="contactos__header">
        <div class="contactos__header-items">
            <a href="nuevoContacto.php"><img class="contactos__header-img" src="/assets/img/Smiley.png"
                                             alt="avatar"></a>
            <p>Contacto</p>
            <a><img class="ncontacto__header-img" src="" alt="foto_perfil"></a>
        </div>
    </section>
    <section>
            <!-- este campo recoge la id que vamos a modificar, arriba en variables la tenemos en un isset -->
            <input type="hidden" name="id" value="<?= $id;?>">
            <div>
                <label for="picture">
                    <img src="<?php echo $picture; ?>">
                </label>
                <label for="nombre">
                    <p name="nombre"><?php echo $nombre; ?></p>
                </label>
                <label for="telefono">
                    teléfono:
                    <p name="telefono"><?php echo $telefono; ?></p>
                </label>
                <label for="email">
                     <p name="email"><?php echo $email; ?></p>
                </label>
                <label for="notas">
                    notas:
                     <p name="notas"><?php echo $notas; ?></p>
                </label>
            </div>
            <div class="login__button login__button-login">
                    <a href="modificar.php?id=<?= $id;?>&nombre=<?= $nombre;?>&telefono=<?= $telefono;?>&email=<?= $email; ?>&notas=<?= $notas; ?>&picture=<?= $picture; ?>" class="login__button-input"> Editar </a>
                <a href="borrar.php?id=<?= $id;?>" class="login__button-input"> Eliminar </a>
            </div>
    </section>
</main>
<?php require_once("componentes/footer.php"); ?>

