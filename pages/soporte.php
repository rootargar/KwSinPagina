<?php
$tituloPagina = 'Soporte - KwSin Portal Corporativo';
include '../includes/header.php';
?>

<div class="container">
    <div class="section-header">
        <h1>🆘 Soporte Técnico</h1>
        <p>Solicita ayuda y soporte técnico</p>
    </div>

    <div class="content-box">
        <h2>Acceso al Sistema de Soporte</h2>
        <p>Utiliza el siguiente enlace para acceder al sistema de tickets de soporte.</p>

        <div class="links-list">
            <a href="#" class="link-item" target="_blank">
                <div>
                    <h3>Ir al Sistema de Soporte</h3>
                    <p>Crea y consulta tickets de soporte técnico</p>
                </div>
            </a>
        </div>
    </div>

    <div class="content-box">
        <h2>Información de Contacto</h2>
        <p><strong>Horario de atención:</strong> Lunes a Viernes de 8:00 AM a 6:00 PM</p>
        <p><strong>Correo electrónico:</strong> soporte@kwsin.com</p>
        <p><strong>Teléfono:</strong> (555) 123-4567</p>
    </div>

    <div class="content-box">
        <h2>Preguntas Frecuentes</h2>
        <div style="line-height: 2;">
            <details style="margin-bottom: 1rem;">
                <summary style="cursor: pointer; font-weight: 600;">¿Cómo crear un ticket de soporte?</summary>
                <p style="margin-top: 0.5rem; padding-left: 1rem;">Accede al sistema de soporte y haz clic en "Nuevo Ticket". Completa el formulario con los detalles de tu problema.</p>
            </details>

            <details style="margin-bottom: 1rem;">
                <summary style="cursor: pointer; font-weight: 600;">¿Cuánto tiempo tarda la respuesta?</summary>
                <p style="margin-top: 0.5rem; padding-left: 1rem;">El tiempo de respuesta promedio es de 2-4 horas laborales dependiendo de la prioridad del ticket.</p>
            </details>

            <details style="margin-bottom: 1rem;">
                <summary style="cursor: pointer; font-weight: 600;">¿Dónde puedo ver el estatus de mi ticket?</summary>
                <p style="margin-top: 0.5rem; padding-left: 1rem;">En el sistema de soporte podrás ver todos tus tickets y su estatus actual en la sección "Mis Tickets".</p>
            </details>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
