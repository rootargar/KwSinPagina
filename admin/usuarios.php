&lt;?php
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
?&gt;

&lt;div class="container"&gt;
    &lt;div class="section-header"&gt;
        &lt;h1&gt;⚙️ Administración de Usuarios&lt;/h1&gt;
        &lt;p&gt;Gestiona usuarios, roles y permisos del sistema&lt;/p&gt;
    &lt;/div&gt;

    &lt;?php if (isset($error)): ?&gt;
        &lt;div class="alert alert-error"&gt;&lt;?php echo htmlspecialchars($error); ?&gt;&lt;/div&gt;
    &lt;?php endif; ?&gt;

    &lt;?php if (isset($success)): ?&gt;
        &lt;div class="alert alert-success"&gt;&lt;?php echo htmlspecialchars($success); ?&gt;&lt;/div&gt;
    &lt;?php endif; ?&gt;

    &lt;div class="content-box"&gt;
        &lt;button class="btn btn-primary" onclick="abrirModalUsuario()"&gt;+ Nuevo Usuario&lt;/button&gt;
    &lt;/div&gt;

    &lt;div class="table-container"&gt;
        &lt;table&gt;
            &lt;thead&gt;
                &lt;tr&gt;
                    &lt;th&gt;ID&lt;/th&gt;
                    &lt;th&gt;Usuario&lt;/th&gt;
                    &lt;th&gt;Rol&lt;/th&gt;
                    &lt;th&gt;Acciones&lt;/th&gt;
                &lt;/tr&gt;
            &lt;/thead&gt;
            &lt;tbody&gt;
                &lt;?php
                if ($stmt === false) {
                    echo '&lt;tr&gt;&lt;td colspan="4"&gt;Error al cargar usuarios&lt;/td&gt;&lt;/tr&gt;';
                } else {
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)):
                        $esMismoUsuario = ($row['IdUsuario'] == $_SESSION['id_usuario']);
                ?&gt;
                &lt;tr&gt;
                    &lt;td&gt;&lt;?php echo htmlspecialchars($row['IdUsuario']); ?&gt;&lt;/td&gt;
                    &lt;td&gt;&lt;?php echo htmlspecialchars($row['Usuario']); ?&gt;&lt;/td&gt;
                    &lt;td&gt;
                        &lt;span style="padding: 0.25rem 0.75rem; border-radius: 4px; background-color: &lt;?php echo $row['Rol'] === 'Administrador' ? 'var(--azul-principal)' : 'var(--gris-medio)'; ?&gt;; color: white; font-size: 0.813rem;"&gt;
                            &lt;?php echo htmlspecialchars($row['Rol']); ?&gt;
                        &lt;/span&gt;
                    &lt;/td&gt;
                    &lt;td&gt;
                        &lt;div class="table-actions"&gt;
                            &lt;button class="btn btn-sm btn-secondary" onclick='editarUsuario(&lt;?php echo json_encode($row); ?&gt;)'&gt;Editar&lt;/button&gt;

                            &lt;?php if (!$esMismoUsuario): ?&gt;
                            &lt;form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')"&gt;
                                &lt;input type="hidden" name="accion" value="eliminar"&gt;
                                &lt;input type="hidden" name="id_usuario" value="&lt;?php echo $row['IdUsuario']; ?&gt;"&gt;
                                &lt;button type="submit" class="btn btn-sm btn-danger"&gt;Eliminar&lt;/button&gt;
                            &lt;/form&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                    &lt;/td&gt;
                &lt;/tr&gt;
                &lt;?php
                    endwhile;
                    sqlsrv_free_stmt($stmt);
                }
                ?&gt;
            &lt;/tbody&gt;
        &lt;/table&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;!-- Modal para crear/editar usuario --&gt;
&lt;div id="modalUsuario" class="modal"&gt;
    &lt;div class="modal-content"&gt;
        &lt;div class="modal-header"&gt;
            &lt;h2 id="modalTitulo"&gt;Nuevo Usuario&lt;/h2&gt;
            &lt;button class="modal-close" onclick="cerrarModalUsuario()"&gt;&amp;times;&lt;/button&gt;
        &lt;/div&gt;
        &lt;form method="POST"&gt;
            &lt;input type="hidden" name="accion" id="accion" value="crear"&gt;
            &lt;input type="hidden" name="id_usuario" id="id_usuario"&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="usuario"&gt;Usuario&lt;/label&gt;
                &lt;input type="text" id="usuario" name="usuario" required&gt;
            &lt;/div&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="contrasena"&gt;Contraseña&lt;/label&gt;
                &lt;input type="password" id="contrasena" name="contrasena"&gt;
                &lt;small style="color: var(--gris-medio);"&gt;Dejar en blanco para mantener la contraseña actual (solo al editar)&lt;/small&gt;
            &lt;/div&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="rol"&gt;Rol&lt;/label&gt;
                &lt;select id="rol" name="rol" required&gt;
                    &lt;option value=""&gt;Seleccionar rol...&lt;/option&gt;
                    &lt;option value="Administrador"&gt;Administrador&lt;/option&gt;
                    &lt;option value="Usuario"&gt;Usuario&lt;/option&gt;
                &lt;/select&gt;
            &lt;/div&gt;

            &lt;div style="display: flex; gap: 1rem; justify-content: flex-end;"&gt;
                &lt;button type="button" class="btn btn-secondary" onclick="cerrarModalUsuario()"&gt;Cancelar&lt;/button&gt;
                &lt;button type="submit" class="btn btn-primary"&gt;Guardar&lt;/button&gt;
            &lt;/div&gt;
        &lt;/form&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;?php include '../includes/footer.php'; ?&gt;
