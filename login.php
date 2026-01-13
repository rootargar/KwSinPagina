&lt;?php
session_start();

// Si ya está autenticado, redirigir al inicio
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/conexion.php';

    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    if (!empty($usuario) && !empty($contrasena)) {
        $sql = "SELECT IdUsuario, Usuario, Rol FROM Usuarios WHERE Usuario = ? AND Contrasena = ?";
        $params = array($usuario, $contrasena);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $error = 'Error al procesar la solicitud';
        } else {
            if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $_SESSION['id_usuario'] = $row['IdUsuario'];
                $_SESSION['usuario'] = $row['Usuario'];
                $_SESSION['rol'] = $row['Rol'];

                header('Location: index.php');
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
            sqlsrv_free_stmt($stmt);
        }
    } else {
        $error = 'Por favor ingrese usuario y contraseña';
    }
}
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="es"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Iniciar Sesión - KwSin&lt;/title&gt;
    &lt;link rel="stylesheet" href="assets/css/style.css"&gt;
&lt;/head&gt;
&lt;body class="login-page"&gt;
    &lt;div class="login-container"&gt;
        &lt;div class="login-box"&gt;
            &lt;img src="assets/img/kwdaf.png" alt="Logo KwSin" class="login-logo"&gt;
            &lt;h1&gt;Iniciar Sesión&lt;/h1&gt;

            &lt;?php if ($error): ?&gt;
                &lt;div class="alert alert-error"&gt;
                    &lt;?php echo htmlspecialchars($error); ?&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;form method="POST" action="login.php"&gt;
                &lt;div class="form-group"&gt;
                    &lt;label for="usuario"&gt;Usuario&lt;/label&gt;
                    &lt;input type="text" id="usuario" name="usuario" required autofocus&gt;
                &lt;/div&gt;

                &lt;div class="form-group"&gt;
                    &lt;label for="contrasena"&gt;Contraseña&lt;/label&gt;
                    &lt;input type="password" id="contrasena" name="contrasena" required&gt;
                &lt;/div&gt;

                &lt;button type="submit" class="btn btn-primary btn-block"&gt;Iniciar Sesión&lt;/button&gt;
            &lt;/form&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;
