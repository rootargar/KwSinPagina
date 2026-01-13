// ==========================================
// Funciones para Modal de Usuario
// ==========================================

function abrirModalUsuario() {
    const modal = document.getElementById('modalUsuario');
    const form = modal.querySelector('form');

    // Resetear formulario
    form.reset();
    document.getElementById('modalTitulo').textContent = 'Nuevo Usuario';
    document.getElementById('accion').value = 'crear';
    document.getElementById('id_usuario').value = '';
    document.getElementById('contrasena').required = true;

    // Mostrar modal
    modal.classList.add('active');
}

function cerrarModalUsuario() {
    const modal = document.getElementById('modalUsuario');
    modal.classList.remove('active');
}

function editarUsuario(usuario) {
    const modal = document.getElementById('modalUsuario');

    // Llenar formulario con datos del usuario
    document.getElementById('modalTitulo').textContent = 'Editar Usuario';
    document.getElementById('accion').value = 'editar';
    document.getElementById('id_usuario').value = usuario.IdUsuario;
    document.getElementById('usuario').value = usuario.Usuario;
    document.getElementById('rol').value = usuario.Rol;
    document.getElementById('contrasena').value = '';
    document.getElementById('contrasena').required = false;

    // Mostrar modal
    modal.classList.add('active');
}

// ==========================================
// Funciones para Modal de Comunicado
// ==========================================

function abrirModalComunicado() {
    const modal = document.getElementById('modalComunicado');
    const form = modal.querySelector('form');

    // Resetear formulario
    form.reset();
    document.getElementById('modalTitulo').textContent = 'Nuevo Comunicado';
    document.getElementById('id_comunicado').value = '';

    // Mostrar modal
    modal.classList.add('active');
}

function cerrarModalComunicado() {
    const modal = document.getElementById('modalComunicado');
    modal.classList.remove('active');
}

function editarComunicado(comunicado) {
    const modal = document.getElementById('modalComunicado');

    // Llenar formulario con datos del comunicado
    document.getElementById('modalTitulo').textContent = 'Editar Comunicado';
    document.getElementById('id_comunicado').value = comunicado.IdComunicado;
    document.getElementById('titulo').value = comunicado.Titulo;
    document.getElementById('contenido').value = comunicado.Contenido;

    // Mostrar modal
    modal.classList.add('active');
}

// ==========================================
// Cerrar modales al hacer clic fuera
// ==========================================

window.addEventListener('click', function(event) {
    // Modal de usuario
    const modalUsuario = document.getElementById('modalUsuario');
    if (modalUsuario && event.target === modalUsuario) {
        cerrarModalUsuario();
    }

    // Modal de comunicado
    const modalComunicado = document.getElementById('modalComunicado');
    if (modalComunicado && event.target === modalComunicado) {
        cerrarModalComunicado();
    }
});

// ==========================================
// Cerrar modales con tecla ESC
// ==========================================

window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modalUsuario = document.getElementById('modalUsuario');
        if (modalUsuario && modalUsuario.classList.contains('active')) {
            cerrarModalUsuario();
        }

        const modalComunicado = document.getElementById('modalComunicado');
        if (modalComunicado && modalComunicado.classList.contains('active')) {
            cerrarModalComunicado();
        }
    }
});

// ==========================================
// Validación de formularios
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    // Agregar validación visual a inputs
    const inputs = document.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.addEventListener('invalid', function() {
            this.style.borderColor = 'var(--rojo-error)';
        });

        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.style.borderColor = 'var(--gris-claro)';
            }
        });
    });

    // Auto-ocultar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
});

// ==========================================
// Confirmaciones de eliminación
// ==========================================

function confirmarEliminacion(mensaje) {
    return confirm(mensaje || '¿Estás seguro de que deseas eliminar este elemento?');
}

// ==========================================
// Utilidades
// ==========================================

// Función para formatear fechas
function formatearFecha(fecha) {
    const opciones = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(fecha).toLocaleDateString('es-MX', opciones);
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    const tiposClases = {
        'info': 'alert-info',
        'success': 'alert-success',
        'error': 'alert-error'
    };

    const alerta = document.createElement('div');
    alerta.className = `alert ${tiposClases[tipo]}`;
    alerta.textContent = mensaje;
    alerta.style.position = 'fixed';
    alerta.style.top = '100px';
    alerta.style.right = '20px';
    alerta.style.zIndex = '9999';
    alerta.style.maxWidth = '400px';

    document.body.appendChild(alerta);

    setTimeout(() => {
        alerta.style.opacity = '0';
        alerta.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            document.body.removeChild(alerta);
        }, 500);
    }, 3000);
}

// Prevenir envío doble de formularios
let formularioEnviado = false;

document.addEventListener('submit', function(event) {
    if (formularioEnviado) {
        event.preventDefault();
        return false;
    }

    const form = event.target;
    if (form.tagName === 'FORM') {
        formularioEnviado = true;

        // Restablecer después de 3 segundos
        setTimeout(() => {
            formularioEnviado = false;
        }, 3000);
    }
});
