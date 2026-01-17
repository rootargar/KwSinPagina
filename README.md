# Portal Corporativo KwSin

Portal web corporativo empresarial desarrollado con PHP, MSSQL Server, HTML, CSS y JavaScript.

## Características

- **Sistema de autenticación** con roles (Administrador y Usuario)
- **Gestión de comunicados** con imágenes
- **Panel de administración** de usuarios
- **Diseño minimalista** con paleta corporativa (azul y gris)
- **Navegación intuitiva** con menú superior fijo
- **Responsive design** para dispositivos móviles

## Tecnologías

- **Backend:** PHP 7.4+
- **Base de datos:** Microsoft SQL Server
- **Frontend:** HTML5, CSS3, JavaScript ES6
- **Servidor web:** Apache/IIS con soporte PHP

## Estructura del Proyecto

```
KwSinPagina/
├── admin/
│   └── usuarios.php          # Gestión de usuarios (solo admin)
├── assets/
│   ├── css/
│   │   └── style.css         # Estilos corporativos
│   ├── js/
│   │   └── main.js          # JavaScript para interactividad
│   └── img/
│       └── kwdaf.png        # Logo de la empresa
├── config/
│   └── conexion.php         # Configuración de conexión a BD
├── includes/
│   ├── auth.php             # Funciones de autenticación
│   ├── header.php           # Header con menú de navegación
│   └── footer.php           # Footer del sitio
├── pages/
│   ├── comunicados.php      # Gestión de comunicados
│   ├── herramientas.php     # Enlaces a herramientas
│   ├── rh.php              # Recursos Humanos
│   ├── crm.php             # Sistema CRM
│   ├── dms.php             # Gestión Documental
│   └── soporte.php         # Soporte técnico
├── uploads/
│   └── comunicados/         # Imágenes de comunicados
├── index.php                # Página de inicio
├── login.php               # Página de inicio de sesión
└── logout.php              # Cerrar sesión
```

## Instalación

### Requisitos Previos

1. Servidor web (Apache o IIS) con PHP 7.4+
2. Microsoft SQL Server
3. Extensión PHP para SQL Server (sqlsrv)

### Pasos de Instalación

1. **Clonar o descargar el proyecto** en el directorio del servidor web

2. **Configurar la base de datos:**

   Ejecutar el siguiente script SQL para crear la tabla de usuarios:

   ```sql
   CREATE TABLE Usuarios (
       IdUsuario INT IDENTITY(1,1) PRIMARY KEY,
       Usuario NVARCHAR(50) NOT NULL UNIQUE,
       Contrasena NVARCHAR(50) NOT NULL,
       Rol NVARCHAR(20) NOT NULL
   );

   -- Insertar usuarios de prueba (CREDENCIALES FICTICIAS)
   -- ADVERTENCIA: Cambiar estas credenciales en producción
   INSERT INTO Usuarios (Usuario, Contrasena, Rol)
   VALUES
   ('admin', 'admin123', 'Administrador'), -- FICTICIO
   ('usuario1', 'user123', 'Usuario'); -- FICTICIO
   ```

3. **Configurar la conexión a la base de datos:**

   Copiar `config/conexion.example.php` como `config/conexion.php` y editar con las credenciales reales de tu servidor:

   ```php
   // IMPORTANTE: Usar credenciales reales, no los valores de ejemplo
   $serverName = "TU_SERVIDOR"; // FICTICIO - Reemplazar con tu servidor real
   $connectionOptions = array(
       "Database" => "TU_BASE_DE_DATOS", // FICTICIO - Reemplazar con tu BD
       "Uid" => "TU_USUARIO", // FICTICIO - Reemplazar con tu usuario
       "PWD" => "TU_CONTRASEÑA" // FICTICIO - Reemplazar con tu contraseña
   );
   ```

   **ADVERTENCIA DE SEGURIDAD:** NUNCA subir el archivo `conexion.php` con credenciales reales al repositorio.

4. **Configurar permisos de escritura:**

   Asegurarse de que la carpeta `uploads/comunicados/` tenga permisos de escritura.

5. **Acceder al sistema:**

   Abrir en el navegador: `http://localhost/KwSinPagina/`

## Credenciales de Acceso por Defecto (FICTICIAS - Solo para Pruebas)

**IMPORTANTE:** Estas son credenciales de EJEMPLO únicamente. Cambiarlas inmediatamente en producción.

- **Administrador:**
  - Usuario: `admin` (FICTICIO)
  - Contraseña: `admin123` (FICTICIO)

- **Usuario Regular:**
  - Usuario: `usuario1` (FICTICIO)
  - Contraseña: `user123` (FICTICIO)

**ADVERTENCIA:** Eliminar o cambiar estas credenciales antes de desplegar en producción.

## Funcionalidades por Rol

### Administrador
- Acceso completo a todas las secciones
- Crear, editar y eliminar comunicados
- Gestionar usuarios (crear, editar, eliminar)
- Asignar roles a usuarios

### Usuario Regular
- Ver comunicados
- Acceder a herramientas
- Consultar información de RH
- Acceder a CRM, DMS y Soporte

## Secciones del Portal

### 1. Inicio
Página principal con tarjetas de acceso rápido a todas las secciones.

### 2. Comunicados
- Ver comunicados publicados con imágenes
- Administradores pueden crear, editar y eliminar comunicados
- Soporte para múltiples imágenes por comunicado

### 3. Herramientas
Enlaces a:
- Cotizador
- Consulta de Clientes

### 4. Recursos Humanos (RH)
- Calendario de vacaciones
- Cumpleaños del mes
- Enlace a cursos de capacitación

### 5. CRM
Enlace al sistema de gestión de relaciones con clientes.

### 6. DMS
Enlace al sistema de gestión documental.

### 7. Soporte
- Información de contacto
- Preguntas frecuentes
- Enlace al sistema de tickets

### 8. Administración de Usuarios (Solo Administrador)
- Alta de usuarios
- Edición de usuarios
- Eliminación de usuarios
- Asignación de roles

## Paleta de Colores

- **Azul Principal:** #2563eb
- **Azul Oscuro:** #1e40af
- **Azul Claro:** #3b82f6
- **Gris Oscuro:** #374151
- **Gris Medio:** #6b7280
- **Gris Claro:** #e5e7eb
- **Gris Fondo:** #f9fafb

## Seguridad

- Autenticación basada en sesiones PHP
- Control de acceso por roles
- Verificación de permisos en cada página
- Protección contra SQL injection (prepared statements)
- Validación de formularios en cliente y servidor

## Personalización

### Cambiar el Logo
Reemplazar el archivo `assets/img/kwdaf.png` con tu propio logo.

### Modificar Colores
Editar las variables CSS en `assets/css/style.css`:

```css
:root {
    --azul-principal: #2563eb;
    --azul-oscuro: #1e40af;
    /* ... */
}
```

### Agregar Nuevas Secciones
1. Crear archivo PHP en `pages/`
2. Incluir `header.php` y `footer.php`
3. Agregar enlace en el menú editando `includes/header.php`

## Notas Importantes

- **Contraseñas:** Actualmente se almacenan en texto plano. Para producción, implementar hashing (password_hash/password_verify).
- **URLs de Herramientas:** Actualizar los enlaces `href="#"` en las páginas de secciones con las URLs reales de los sistemas.
- **Imágenes de RH:** Agregar imágenes de vacaciones y cumpleaños en la página `pages/rh.php`.

## Mantenimiento

### Agregar Comunicado
1. Iniciar sesión como Administrador
2. Ir a "Comunicados"
3. Clic en "Nuevo Comunicado"
4. Completar formulario y adjuntar imágenes
5. Guardar

### Gestionar Usuarios
1. Iniciar sesión como Administrador
2. Ir a "Usuarios" en el menú
3. Usar botones para crear, editar o eliminar usuarios

## Soporte Técnico

Para problemas técnicos o consultas, contactar al equipo de desarrollo.

## Licencia

© 2026 KwSin - Todos los derechos reservados
