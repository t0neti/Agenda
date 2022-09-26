<?php
//Imports
require_once ('logica/conexion.php');
require_once ('logica/validadores.php');

// Variables
// Capturamos los inputs y las guardamos en variables.
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
$remember = isset($_REQUEST['remember']) ? $_REQUEST['password'] : null;
 // Creamos una variable con un array vacía para mostrar los errores de las validaciones
$errores = [];

// Preguntamos que método estamos usando y si email, password y repetir password no sean null
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email !== null && $password !== null) {

    // Validacionese en caso de que algo pase
    if (validar_requerido($email)) {
        $errores[] = 'Es necesario el email';
    }
    if (validar_requerido($password)) {
        $errores[] = 'Es necesaria la contraseña';
    }

    // Si no hay errores, procedemos
    if (count($errores) === 0) {
        // ¿hay algun usuario con estas claves?
        $miConsulta = $miPDO->prepare('SELECT id, password FROM usuario WHERE email = :email AND email_validado = 1');

        $miConsulta->execute([
            'email' => $email
        ]);

        $resultado = $miConsulta->fetch();

        // Si resultado ha sido comprobado y la contraseña es verificada
        if ($resultado && password_verify($password, $resultado['password'])) {
            // Creamos sesion
            session_start();

            $tokenSesion = bin2hex(openssl_random_pseudo_bytes(16));
            $nuevoToken = $miPDO->prepare('INSERT INTO sesiones (id_usuario, token_sesion) values (:id_usuario, :token_sesion)');
            $nuevoToken->execute([
                'id_usuario' => $resultado['id'],
                'token_sesion' => $tokenSesion
            ]);

            $_SESSION['usuario'] = $tokenSesion;
            $_SESSION['id_usuario'] = $resultado['id'];

            header('location: contactos.php');

        } else {
            $errores[] = 'La contraseña o email no validos';
        }
    }
}

?>
<?php require_once("componentes/head.php"); ?>
<?php require_once("componentes/header-login.php"); ?>

<main class="main main-container">
    <section>
        <form action="">
            <div class="google__button">
                <!-- Botón google -->
                <input class="google__button-input" type="submit" value="Log in with google" placeholder="Log in with google">
            </div>
        </form>
    </section>
    <hr>
    <section>
        <form method="post" action="login.php">
            <div class="form__input">
             <!-- Campo de Email -->
                <label>
                    <p class="form__input-text">Email</p>
                    <input class="form__input-box" type="text" name="email" placeholder="ejemplo@gmail.com">
                </label>
            </div>
            <div class="form__input" id="app">
            <!-- Campo de Contraseña -->
                <p class="form__input-text">Contraseña</p>
                <input class="form__input-box" type="text" id="password" name="password" placeholder="Enter Password" required>
            </div>
             <div>
                <!-- Campo de Contraseña -->
              <!--  <label>
                    <p class="form__input-text">Repetir contraseña</p>
                    <input class="form__input-box" type="text" name="password-repeat" placeholder="Al menos 8 caracteres">
                </label>
            </div> -->
            <div class="form__input-cbflex">
                <!-- campo de checkbox -->
                    <input class="form__input-cb" type="checkbox" id="remember" name="remember" value="checked">
                    <p class="form__input-cbtext">Recuérdame</p>
            </div>
            <div class="login__button login__button-login">
            <!-- Botón submit -->
                 <input class="login__button-input" type="submit" value="Log in">
             </div>
        </form>
    </section>
    <section>
        <div class="text__question-display">
            <a class="text__question-pw" href="recuperar-password.php">¿Has olvidado tu contraseña?</a>
        </div>
        <hr>
    </section>
    <section>
        <div class="text__question-display">
            <p class="text__question-acc">¿Todavía no tienes cuenta?</p>
        </div>
        <div class="text__question-display">
            <p><a class="text__question-pw" href="registro.php">Sign up</a></p>
        </div>
    </section>
</main>
</body>
</html>

