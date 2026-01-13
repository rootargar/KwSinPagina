&lt;?php
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
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="es"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;&lt;?php echo $tituloPagina ?? 'KwSin - Portal Corporativo'; ?&gt;&lt;/title&gt;
    &lt;link rel="stylesheet" href="/assets/css/style.css"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;header class="header-fixed"&gt;
        &lt;div class="header-container"&gt;
            &lt;div class="header-logo"&gt;
                &lt;a href="/index.php"&gt;
                    &lt;img src="/assets/img/kwdaf.png" alt="Logo KwSin"&gt;
                &lt;/a&gt;
            &lt;/div&gt;

            &lt;nav class="header-nav"&gt;
                &lt;ul&gt;
                    &lt;li&gt;&lt;a href="/index.php" class="&lt;?php echo ($paginaActual === 'index.php') ? 'active' : ''; ?&gt;"&gt;Inicio&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/comunicados.php" class="&lt;?php echo ($paginaActual === 'comunicados.php') ? 'active' : ''; ?&gt;"&gt;Comunicados&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/herramientas.php" class="&lt;?php echo ($paginaActual === 'herramientas.php') ? 'active' : ''; ?&gt;"&gt;Herramientas&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/rh.php" class="&lt;?php echo ($paginaActual === 'rh.php') ? 'active' : ''; ?&gt;"&gt;RH&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/crm.php" class="&lt;?php echo ($paginaActual === 'crm.php') ? 'active' : ''; ?&gt;"&gt;CRM&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/dms.php" class="&lt;?php echo ($paginaActual === 'dms.php') ? 'active' : ''; ?&gt;"&gt;DMS&lt;/a&gt;&lt;/li&gt;
                    &lt;li&gt;&lt;a href="/pages/soporte.php" class="&lt;?php echo ($paginaActual === 'soporte.php') ? 'active' : ''; ?&gt;"&gt;Soporte&lt;/a&gt;&lt;/li&gt;
                    &lt;?php if ($esAdmin): ?&gt;
                        &lt;li&gt;&lt;a href="/admin/usuarios.php" class="&lt;?php echo ($paginaActual === 'usuarios.php') ? 'active' : ''; ?&gt;"&gt;Usuarios&lt;/a&gt;&lt;/li&gt;
                    &lt;?php endif; ?&gt;
                &lt;/ul&gt;
            &lt;/nav&gt;

            &lt;div class="header-user"&gt;
                &lt;span class="user-name"&gt;&lt;?php echo htmlspecialchars($usuario); ?&gt;&lt;/span&gt;
                &lt;span class="user-role"&gt;(&lt;?php echo htmlspecialchars($rol); ?&gt;)&lt;/span&gt;
                &lt;a href="/logout.php" class="btn-logout"&gt;Cerrar Sesión&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/header&gt;

    &lt;main class="main-content"&gt;
