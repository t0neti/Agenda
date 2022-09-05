<?php
//Imports
require_once('logica/conexion.php');

session_start();

$borrado = isset($_REQUEST['borrado']) ? intval($_REQUEST['borrado']) : null;

if(!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
} else {
    // Prepara SELECT
    $miContacto = $miPDO->prepare('SELECT * FROM contactos WHERE estado = 1 AND id_usuario = :id_usuario ORDER BY nombre ASC');
// Ejecuta consulta
    $miContacto->execute([
            'id_usuario' => $_SESSION['id_usuario']
        ]);
}
    $listaContactos = $miContacto->fetchAll();

if ($borrado) {
    echo 'Se ha eliminado un contacto';
}
?>
<?php require_once("componentes/head.php"); ?>
    <main class="main main-container">
        <section class="contactos__header">
            <div class="contactos__header-items">
                <a href="nuevoContacto.php"><img class="contactos__header-img" src="/assets/img/Smiley.png" alt="avatar"></a>
                <p>Contactos</p>
                <a><img class="ncontacto__header-img" src=""  alt="foto_perfil"></a>
            </div>
        </section>
        <section>
            <div class="contactos__search">
                <input type="text" class="contactos__search-box" placeholder="      Buscar ">
            </div>
        </section>
        <section>
            <aside>
                <nav>
                    <ul>
                        <li>A</li>
                        <li>B</li>
                        <li>C</li>
                        <li>D</li>
                        <li>E</li>
                        <li>F</li>
                        <li>G</li>
                        <li>H</li>
                        <li>I</li>
                        <li>J</li>
                        <li>K</li>
                        <li>L</li>
                        <li>M</li>
                        <li>N</li>
                        <li>O</li>
                        <li>P</li>
                        <li>Q</li>
                        <li>R</li>
                        <li>S</li>
                        <li>T</li>
                        <li>U</li>
                        <li>V</li>
                        <li>W</li>
                        <li>X</li>
                        <li>Y</li>
                        <li>Z</li>
                    </ul>
                </nav>
            </aside>
        </section>
        <section class="contactos__overflow">
            <!--
            <div>
                <h2>Recientes</h2>
            </div>
            -->
            <div>

            </div>
            <div>
                <h2>Todos</h2>
            </div>
            <hr>
            <?php foreach ($listaContactos as $contact => $fila): ?>
            <div>
                <a href="detalle-contacto.php?id=<?= $fila['id'];?>&nombre=<?= $fila['nombre'];?>&telefono=<?= $fila['telefono'];?>&email=<?= $fila['email']; ?>&notas=<?= $fila['notas']; ?>&picture=<?= $fila['picture']; ?>">
                    <?php require('componentes/contacto.php');?>
                </a>
            </div>
            <?php endforeach; ?>
        </section>
    </main>
<?php require_once("componentes/footer.php"); ?>