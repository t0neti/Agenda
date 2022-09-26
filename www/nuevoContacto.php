<?php

//Imports
require_once('logica/conexion.php');
require_once('logica/validadores.php');
require_once('logica/subirFichero.php');

// Variables
// Capturamos los inputs y las guardamos en variables.
$nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : "";
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : "";
$telefono = isset($_REQUEST['telefono']) ? $_REQUEST['telefono'] : "";
$notas = isset($_REQUEST['notas']) ? $_REQUEST['notas'] : "";
$isFavorite = isset($_REQUEST['is_favorite']) && $_REQUEST['is_favorite'] === "on" ? 1 : 0;
$avatar = isset($_REQUEST['avatar']) ? $_REQUEST['avatar'] : "";
// Creamos una variable con un array vacía para mostrar los errores de las validaciones
$errores = [];

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario'])) {

    if (validar_requerido($nombre)) {
        $errores[] = 'Debe haber un nombre';
    }
    if (validar_requerido($telefono)) {
        $errores[] = 'Es necesario un teléfono';
    }
    //------------------------- Comprobar que el teléfono no se repita
    $consultarTelefono = $miPDO->prepare('SELECT COUNT(*) AS telefono_existe FROM contactos WHERE telefono = :telefono AND estado = 1');

    // ejecutamos la consulta
    $consultarTelefono->execute([
        'telefono' => $telefono
    ]);

    // ambos parámetros que no quiero que existan iguales los guardo en un diccionario
    $telefonoExiste = $consultarTelefono->fetch();

    // Ponemos un error si hay duplicados
    if ($telefonoExiste['telefono_existe']) {
        $errores[] = 'teléfono  ya registrado';
    }

    if (isset($_FILES)) {
        //-----------------------------------------------------
        //  Recoger avatar
        //-----------------------------------------------------

        // Verifica si existe el directorio, y en caso contrario lo crea
        if (!is_dir(PATH_AVATAR_THUMBNAIL)) {
            mkdir(PATH_AVATAR_THUMBNAIL, 0775, true);
        }
        // Definir la ruta final del archivo
        $nombreFoto = sha1_file($_FILES['avatar']['tmp_name']) . basename($_FILES['avatar']['name']);
        $ficheroSubido = PATH_AVATAR . $nombreFoto;

        //-----------------------------------------------------
        //  Control de errores
        //-----------------------------------------------------

        // Tamanyo maximo
        if ($_FILES['avatar']['size'] > MAX_SIZE_AVATAR) {
            $errorAvatar = 1;
        }

        // Solo JPG y PNG
        if ($_FILES['avatar']['type'] !== 'image/png' && $_FILES['avatar']['type'] !== 'image/jpeg') {
            $errorAvatar = 2;
        }

        // Obligatorio
        if ($_FILES['avatar']['size'] === 0) {
            $errorAvatar = 3;
        }

        //-----------------------------------------------------
        //  Procesar imagen
        //-----------------------------------------------------

        if ($errorAvatar === 0) {
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $ficheroSubido)) {
                // Mueve el archivo de la carpeta temporal a la ruta definida
                $avatar = $ficheroSubido;

                // Creamos una miniatura
                // No olvides instalarlo con: sudo apt install php-imagick
                $imagen = new Imagick($avatar);

                // Si se proporciona 0 como parámetro de ancho o alto,
                // se mantiene la proporción de aspecto
                $imagen->thumbnailImage(WIDTH_THUMBNAIL, 0);
                $rutaThumbnail = PATH_AVATAR_THUMBNAIL . $nombreFoto;
                file_put_contents($rutaThumbnail, $imagen);
            }
        }
    }
    if ((count($errores) === 0)) {
        $miNuevoContacto = $miPDO->prepare('INSERT INTO contactos (nombre, email, telefono, notas, id_usuario, is_favorite, picture) VALUES (:nombre, :email, :telefono, :notas, :id_usuario, :is_favorite, :picture)');

        $miNuevoContacto->execute([
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'notas' => $notas,
            'is_favorite' => $isFavorite,
            'id_usuario' => $_SESSION['id_usuario'],
            'picture' => $rutaThumbnail

        ]);

        header('location: contactos.php');
    }
}
?>
<?php require_once("componentes/head.php"); ?>
<main class="main main-container">
    <section class="ncontacto__header">
        <div class="header__login-items">
            <img class="header__login-img" src="/assets/img/Smiley.png" alt="avatar">
            <p class="header__login-text">Nuevo</p>
        </div>
    </section>
    <section>
        <form method="post" enctype="multipart/form-data">
            <div class="ncontacto__form-file">
              <!-- <?php if (isset($rutaThumbnail)): ?>
                    <img src="<?= $rutaThumbnail; ?>" class="ncontacto__form-img" alt="mi avatar" width="<?= WIDTH_THUMBNAIL ?>">
                <?php endif; ?> -->
                <input class="ncontacto__input-file" type="file" name="avatar">
            </div>
            <?php if ($errorAvatar === 1): ?>
                <p style="color: red">
                    Tamaño demasiado grande, intente que tenga menos de <?= MAX_SIZE_AVATAR_MB ?>Mb
                </p>
            <?php elseif ($errorAvatar === 2): ?>
                <p style="color: red">
                    Solo admitido imagenes en JPG o PNG.
                </p>
            <?php elseif ($errorAvatar === 3): ?>
                <p style="color: red">
                    Debes incluir una imagen
                </p>
            <?php endif; ?>
            <div>
                <label>
                    <p class="form__input-text">Nombre</p>
                    <input class="form__input-box" type="text" name="nombre" placeholder="Nombre completo"">
                </label>
            </div>
            <div>
                <label>
                    <p class="form__input-text">Email</p>
                    <input class="form__input-box" type="email" name="email" placeholder="ejemplo@gmail.com">
                </label>
            </div>
            <div>
                <label>
                    <p class="form__input-text">Teléfono</p>
                    <input class="form__input-box" type="number" name="telefono" placeholder="(+66) 666 66 66 66">
                </label>
            </div>
            <div class="form__input-cbflex">
                <input class="form__input-cb" type="checkbox" name="is_favorite" id="">
                <p class="form__input-cbtext">Añadir a favoritos</p>
            </div>
            <div>
                <label>
                    <p class="form__input-text">Notas</p>
                    <input class="form__input-box" name="notas" type="text" placeholder="Info adicional" >
                </label>
            </div>
            <div class="login__button">
                <input class="login__button-ncontacto" type="submit" value="Añadir Contacto">
            </div>
        </form>
    </section>
</main>
<?php require_once("componentes/footer.php"); ?>
