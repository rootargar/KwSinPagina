<?php
$tituloPagina = 'Inicio - KwSin Portal Corporativo';
include 'includes/header.php';
?>

<div class="container">
    <div class="welcome-section">
        <h1>Bienvenido al Portal Corporativo KwSin</h1>
        <p>Hola <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>, accede rápidamente a las secciones del portal</p>
    </div>

    <div class="cards-grid">
        <a href="pages/comunicados.php" class="card">
            <div class="card-icon">📢</div>
            <h3>Comunicados</h3>
            <p>Consulta los últimos comunicados y anuncios importantes de la empresa</p>
        </a>

        <a href="pages/herramientas.php" class="card">
            <div class="card-icon">🛠️</div>
            <h3>Herramientas</h3>
            <p>Accede a las herramientas de trabajo: Cotizador y Consulta de Clientes</p>
        </a>

        <a href="pages/rh.php" class="card">
            <div class="card-icon">👥</div>
            <h3>Recursos Humanos</h3>
            <p>Información sobre vacaciones, cumpleaños y capacitación</p>
        </a>

        <a href="pages/crm.php" class="card">
            <div class="card-icon">📊</div>
            <h3>CRM</h3>
            <p>Sistema de gestión de relaciones con clientes</p>
        </a>

        <a href="pages/dms.php" class="card">
            <div class="card-icon">📁</div>
            <h3>DMS</h3>
            <p>Sistema de gestión documental de la empresa</p>
        </a>

        <a href="pages/soporte.php" class="card">
            <div class="card-icon">🆘</div>
            <h3>Soporte</h3>
            <p>Solicita ayuda técnica y soporte para tus herramientas</p>
        </a>

        <?php if ($esAdmin): ?>
        <a href="admin/usuarios.php" class="card card-admin">
            <div class="card-icon">⚙️</div>
            <h3>Administración de Usuarios</h3>
            <p>Gestiona usuarios, roles y permisos del sistema</p>
        </a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
