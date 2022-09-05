<?php
// Imports mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once('vendor/autoload.php');

//Imports
require_once('logica/conexion.php');
require_once('logica/validadores.php');

// Variables
// Capturamos los inputs y las guardamos en variables.
$nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : null;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
$passwordRepeat = isset($_REQUEST['password-repeat']) ? $_REQUEST['password-repeat'] : null;
$acepto = isset($_REQUEST['acepto']);

// Creamos una variable con un array vacía para mostrar los errores de las validaciones
$errores = [];

// preguntamos al server el método
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nombre !== null && $email !== null && $password !== null && $passwordRepeat !== null && $acepto !== 0 ) {

    // Validaciones
    if (validar_requerido($nombre)) {
        $errores[] = 'Es necesario un nombre';
    }
    if (validar_requerido($email)) {
        $errores[] = 'Es necesario el email';
    }
    if (validar_email($email)) {
        $errores[] = 'Tienes que introducir un email válido';
    }
    if (validar_requerido($password)) {
        $errores[] = 'Es necesaria la contraseña';
    }
    if (validar_requerido($passwordRepeat)) {
        $errores[] = 'Es necesario repetir la contraseña';
    }
    if (!validar_contrasenya($password)) {
        $errores[] = 'La contraseña tiene que tener entre 4 y 8 caracteres y al menos un número y letras mayúsculas y minúsculas';
    }
    if ($password !== $passwordRepeat) {
        $errores[] = 'Las contraseñas no coinciden';
    }
    if ($acepto == 0) {
        $errores[] = 'Debes aceptar términos y condiciones de privacidad ';
    }

    //------------------------- Comprobar que el usuario no se repita
    $consultarUsuario = $miPDO->prepare('SELECT  COUNT(*) AS usuario_existe FROM usuario WHERE email = :email AND estado = 1');

    // ejecutamos la consulta
    $consultarUsuario->execute([
        'email' => $email
    ]);

    // ambos parámetros que no quiero que existan iguales los guardo en un diccionario
    $usuarioExiste = $consultarUsuario->fetch();

    // Ponemos un error si hay duplicados
    if ($usuarioExiste['usuario_existe']) {
        $errores[] = 'Email no disponible';
    }

    // Si no hay errores procedemos a encriptar la contraseña, y crear un token
    if (count($errores) === 0) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $token = bin2hex(openssl_random_pseudo_bytes(16));

        // Prepara INSERT en la base de datos
        $miNuevoRegistro = $miPDO->prepare('INSERT INTO usuario (nombre, email, password, token) VALUES (:nombre, :email, :password, :token);');

        // Ejecuta el nuevo registro en la base de datos y lo guardamos.
        $miNuevoRegistro->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password_hash,
            'token' => $token
        ]);

        // Enviamos correo si el registro es todo ok!
        // Crea objeto
        $mail = new PHPMailer();

        try {
            // Configuración servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'mailhog'; // aquí meteremos el host de mail que usaremos( pendiente de buscar)
            $mail->SMTPAuth   = true;
            $mail->Username   = '';
            $mail->Password   = '';
            $mail->SMTPSecure = false;
            $mail->Port       = 1025;  // el expose del docker-compose.yaml

            $mail->setFrom('no-reply@agendaContactos.com', 'AgendaContactos');
            // Quien lo recibe
            $mail->addAddress($email, 'Usuario');

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Verifica tu cuenta';
            $mail->Body    = "Pulsa aqui para validar la cuenta: <a href='http://localhost/validar-token.php?email=$email&token=$token'>pulsa aqui</a>";

            // Enviar
            $mail->send();

            header('Location: http://localhost/login.php');

        } catch (Exception $e) {
            // Errores
            echo $e;
        }
    }
}
;?>
<?php require_once("componentes/head.php"); ?>
<?php require_once("componentes/header-login.php"); ?>

<main class="main main-container">
    <section>
        <form action="">
            <div class="google__button">
                <!-- Botón google -->
                <input class="google__button-input" type="submit" value="Sign up with google" placeholder="Sign up with google">
            </div>
        </form>
    </section>
    <hr>
    <section>
        <form method="POST" action="registro.php">
            <?php require_once("componentes/listar-errores.php"); ?>
            <div class="registro__form_input">
                <!-- Campo de nombre -->
                <label for="nombre">
                    <p class="registro__form_input-text">Nombre</p>
                    <input class="registro__form_input-box" type="text" name="nombre" placeholder="Nombre" value="<?= $nombre ?>">
                </label>
            </div>
            <div class="registro__form_input">
                <!-- Campo de Email -->
                <label>
                    <p class="registro__form_input-text">Email</p>
                    <input class="registro__form_input-box" type="text" name="email" placeholder="ejemplo@gmail.com">
                </label>
            </div>
            <div class="registro__form_input">
                <!-- Campo de Contraseña -->
                <label>
                    <p class="registro__form_input-text">Contraseña</p>
                    <input class="registro__form_input-box" type="text" name="password" placeholder="Al menos 8 caracteres">
                </label>
            </div>
            <div class="registro__form_input">
                <!-- Campo de Contraseña -->
                <label>
                    <p class="registro__form_input-text">Confirmar Contraseña</p>
                    <input class="registro__form_input-box" type="text" name="password-repeat" placeholder="Al menos 8 caracteres">
                </label>
            </div>
            <div class="form__input-cbflex">
                <!-- campo de checkbox -->
                <input class="form__input-cb" type="checkbox" name="acepto" value="1">
                <p class="form__input-cbtext">Acepto términos y condiciones de privacidad</p>
            </div>
            <div class="login__button">
                <!-- Botón submit -->
                <input input class="login__button-input" type="submit" value="Sign up">
            </div>
        </form>
    </section>
    <hr>
    <section>
        <div class="text__question-display">
            <p class="text__question-acc">¿Ya tienes una cuenta?</p>
        </div>
        <div class="text__question-display">
            <p><a class="text__question-pw" href="login.php">Log in</a></p>
        </div>
    </section>
</main>
</body>
</html>
