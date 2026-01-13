<?php
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - KwSin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <img src="assets/img/kwdaf.png" alt="Logo KwSin" class="login-logo">
            <h1>Iniciar Sesión</h1>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
