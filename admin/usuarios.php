<?php
$tituloPagina = 'Administración de Usuarios - KwSin Portal Corporativo';
include '../includes/header.php';
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Verificar que sea administrador
verificarAdministrador();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'crear' || $_POST['accion'] === 'editar') {
            $usuario = $_POST['usuario'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $rol = $_POST['rol'] ?? '';

            if (!empty($usuario) && !empty($rol)) {
                if ($_POST['accion'] === 'crear') {
                    if (empty($contrasena)) {
                        $error = 'La contraseña es requerida para crear un usuario';
                    } else {
                        $sql = "INSERT INTO Usuarios (Usuario, Contrasena, Rol) VALUES (?, ?, ?)";
                        $params = array($usuario, $contrasena, $rol);
                        $stmt = sqlsrv_query($conn, $sql, $params);

                        if ($stmt) {
                            $success = 'Usuario creado exitosamente';
                            sqlsrv_free_stmt($stmt);
                        } else {
                            $error = 'Error al crear el usuario. Puede que ya exista.';
                        }
                    }
                } else {
                    // Editar
                    $id = $_POST['id_usuario'];
                    if (!empty($contrasena)) {
                        $sql = "UPDATE Usuarios SET Usuario = ?, Contrasena = ?, Rol = ? WHERE IdUsuario = ?";
                        $params = array($usuario, $contrasena, $rol, $id);
                    } else {
                        $sql = "UPDATE Usuarios SET Usuario = ?, Rol = ? WHERE IdUsuario = ?";
                        $params = array($usuario, $rol, $id);
                    }

                    $stmt = sqlsrv_query($conn, $sql, $params);

                    if ($stmt) {
                        $success = 'Usuario actualizado exitosamente';
                        sqlsrv_free_stmt($stmt);
                    } else {
                        $error = 'Error al actualizar el usuario';
                    }
                }
            } else {
                $error = 'Todos los campos son requeridos';
            }
        } elseif ($_POST['accion'] === 'eliminar') {
            $id = $_POST['id_usuario'];

            // No permitir eliminar al usuario actual
            if ($id == $_SESSION['id_usuario']) {
                $error = 'No puedes eliminar tu propio usuario';
            } else {
                $sql = "DELETE FROM Usuarios WHERE IdUsuario = ?";
                $stmt = sqlsrv_query($conn, $sql, array($id));

                if ($stmt) {
                    $success = 'Usuario eliminado exitosamente';
                    sqlsrv_free_stmt($stmt);
                } else {
                    $error = 'Error al eliminar el usuario';
                }
            }
        }
    }
}

// Obtener lista de usuarios
$sql = "SELECT IdUsuario, Usuario, Rol FROM Usuarios ORDER BY Usuario";
$stmt = sqlsrv_query($conn, $sql);
?>

<div class="container">
    <div class="section-header">
        <h1>⚙️ Administración de Usuarios</h1>
        <p>Gestiona usuarios, roles y permisos del sistema</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="content-box">
        <button class="btn btn-primary" onclick="abrirModalUsuario()">+ Nuevo Usuario</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt === false) {
                    echo '<tr><td colspan="4">Error al cargar usuarios</td></tr>';
                } else {
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):
                        $esMismoUsuario = ($row['IdUsuario'] == $_SESSION['id_usuario']);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['IdUsuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                    <td>
                        <span style="padding: 0.25rem 0.75rem; border-radius: 4px; background-color: <?php echo $row['Rol'] === 'Administrador' ? 'var(--azul-principal)' : 'var(--gris-medio)'; ?>; color: white; font-size: 0.813rem;">
                            <?php echo htmlspecialchars($row['Rol']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-sm btn-secondary" onclick='editarUsuario(<?php echo json_encode($row); ?>)'>Editar</button>

                            <?php if (!$esMismoUsuario): ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id_usuario" value="<?php echo $row['IdUsuario']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php
                    endwhile;
                    sqlsrv_free_stmt($stmt);
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear/editar usuario -->
<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo">Nuevo Usuario</h2>
            <button class="modal-close" onclick="cerrarModalUsuario()">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="accion" id="accion" value="crear">
            <input type="hidden" name="id_usuario" id="id_usuario">

            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena">
                <small style="color: var(--gris-medio);">Dejar en blanco para mantener la contraseña actual (solo al editar)</small>
            </div>

            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccionar rol...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalUsuario()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
