&lt;?php
$tituloPagina = 'Inicio - KwSin Portal Corporativo';
include 'includes/header.php';
?&gt;

&lt;div class="container"&gt;
    &lt;div class="welcome-section"&gt;
        &lt;h1&gt;Bienvenido al Portal Corporativo KwSin&lt;/h1&gt;
        &lt;p&gt;Hola &lt;strong&gt;&lt;?php echo htmlspecialchars($_SESSION['usuario']); ?&gt;&lt;/strong&gt;, accede rápidamente a las secciones del portal&lt;/p&gt;
    &lt;/div&gt;

    &lt;div class="cards-grid"&gt;
        &lt;a href="pages/comunicados.php" class="card"&gt;
            &lt;div class="card-icon"&gt;📢&lt;/div&gt;
            &lt;h3&gt;Comunicados&lt;/h3&gt;
            &lt;p&gt;Consulta los últimos comunicados y anuncios importantes de la empresa&lt;/p&gt;
        &lt;/a&gt;

        &lt;a href="pages/herramientas.php" class="card"&gt;
            &lt;div class="card-icon"&gt;🛠️&lt;/div&gt;
            &lt;h3&gt;Herramientas&lt;/h3&gt;
            &lt;p&gt;Accede a las herramientas de trabajo: Cotizador y Consulta de Clientes&lt;/p&gt;
        &lt;/a&gt;

        &lt;a href="pages/rh.php" class="card"&gt;
            &lt;div class="card-icon"&gt;👥&lt;/div&gt;
            &lt;h3&gt;Recursos Humanos&lt;/h3&gt;
            &lt;p&gt;Información sobre vacaciones, cumpleaños y capacitación&lt;/p&gt;
        &lt;/a&gt;

        &lt;a href="pages/crm.php" class="card"&gt;
            &lt;div class="card-icon"&gt;📊&lt;/div&gt;
            &lt;h3&gt;CRM&lt;/h3&gt;
            &lt;p&gt;Sistema de gestión de relaciones con clientes&lt;/p&gt;
        &lt;/a&gt;

        &lt;a href="pages/dms.php" class="card"&gt;
            &lt;div class="card-icon"&gt;📁&lt;/div&gt;
            &lt;h3&gt;DMS&lt;/h3&gt;
            &lt;p&gt;Sistema de gestión documental de la empresa&lt;/p&gt;
        &lt;/a&gt;

        &lt;a href="pages/soporte.php" class="card"&gt;
            &lt;div class="card-icon"&gt;🆘&lt;/div&gt;
            &lt;h3&gt;Soporte&lt;/h3&gt;
            &lt;p&gt;Solicita ayuda técnica y soporte para tus herramientas&lt;/p&gt;
        &lt;/a&gt;

        &lt;?php if ($esAdmin): ?&gt;
        &lt;a href="admin/usuarios.php" class="card card-admin"&gt;
            &lt;div class="card-icon"&gt;⚙️&lt;/div&gt;
            &lt;h3&gt;Administración de Usuarios&lt;/h3&gt;
            &lt;p&gt;Gestiona usuarios, roles y permisos del sistema&lt;/p&gt;
        &lt;/a&gt;
        &lt;?php endif; ?&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;?php include 'includes/footer.php'; ?&gt;
