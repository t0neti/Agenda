<?php
// Importamos
require_once('logica/conexion.php');

session_start();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario'])) {

    if ($id) {
        // Busco mi artista por el id
        $miContacto = $miPDO->prepare('SELECT id, COUNT(*) AS existe FROM contactos WHERE id = :id AND estado = 1');

        // Añado el id de mi contacto
        $miContacto->execute([
            'id' => $id
        ]);

        // Preparo los datos
        $contacto = $miContacto->fetch();
        $existe = isset($contacto['existe']) ? $contacto['existe'] !== 0 : false;
    }
        if ($existe) {
            // eliminamos un registro
            $miConsulta = $miPDO->prepare('DELETE FROM contactos WHERE id = :id');

            $miConsulta->execute([
                'id' => $id
            ]);
        }
        // Redireccionamos a contactos.php
        header("Location: contactos.php?borrado=$id");
    }

?>
<?php require_once("componentes/head.php"); ?>
<main>
    <form action="borrar.php" method="POST">
        <input type="hidden" name="id" value="<?= $id;?>">
            <p> Estás seguro que quieres eliminar este contacto?</p>
        <input type="submit" value="Eliminar">
    </form>
</main>
</body>
</html>
