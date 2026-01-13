&lt;?php
session_start();

// Verificar si el usuario está autenticado
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: /login.php');
        exit();
    }
}

// Verificar si el usuario es administrador
function esAdministrador() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';
}

// Verificar que solo administradores accedan
function verificarAdministrador() {
    verificarSesion();
    if (!esAdministrador()) {
        header('Location: /index.php');
        exit();
    }
}
?&gt;
