<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    header('Location: /login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];
$esAdmin = ($rol === 'Administrador');

// Determinar la página actual
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina ?? 'KwSin - Portal Corporativo'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="header-fixed">
        <div class="header-container">
            <div class="header-logo">
                <a href="/index.php">
                    <img src="/assets/img/kwdaf.png" alt="Logo KwSin">
                </a>
            </div>

            <nav class="header-nav">
                <ul>
                    <li><a href="/index.php" class="<?php echo ($paginaActual === 'index.php') ? 'active' : ''; ?>">Inicio</a></li>
                    <li><a href="/pages/comunicados.php" class="<?php echo ($paginaActual === 'comunicados.php') ? 'active' : ''; ?>">Comunicados</a></li>
                    <li><a href="/pages/herramientas.php" class="<?php echo ($paginaActual === 'herramientas.php') ? 'active' : ''; ?>">Herramientas</a></li>
                    <li><a href="/pages/rh.php" class="<?php echo ($paginaActual === 'rh.php') ? 'active' : ''; ?>">RH</a></li>
                    <li><a href="/pages/crm.php" class="<?php echo ($paginaActual === 'crm.php') ? 'active' : ''; ?>">CRM</a></li>
                    <li><a href="/pages/dms.php" class="<?php echo ($paginaActual === 'dms.php') ? 'active' : ''; ?>">DMS</a></li>
                    <li><a href="/pages/soporte.php" class="<?php echo ($paginaActual === 'soporte.php') ? 'active' : ''; ?>">Soporte</a></li>
                    <?php if ($esAdmin): ?>
                        <li><a href="/admin/usuarios.php" class="<?php echo ($paginaActual === 'usuarios.php') ? 'active' : ''; ?>">Usuarios</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="header-user">
                <span class="user-name"><?php echo htmlspecialchars($usuario); ?></span>
                <span class="user-role">(<?php echo htmlspecialchars($rol); ?>)</span>
                <a href="/logout.php" class="btn-logout">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <main class="main-content">
