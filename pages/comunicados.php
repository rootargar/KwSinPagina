&lt;?php
$tituloPagina = 'Comunicados - KwSin Portal Corporativo';
include '../includes/header.php';
require_once '../config/conexion.php';

// Crear tabla de comunicados si no existe
$sqlCreateTable = "
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Comunicados' AND xtype='U')
CREATE TABLE Comunicados (
    IdComunicado INT IDENTITY(1,1) PRIMARY KEY,
    Titulo NVARCHAR(255) NOT NULL,
    Contenido NVARCHAR(MAX),
    Imagenes NVARCHAR(MAX),
    FechaPublicacion DATETIME DEFAULT GETDATE(),
    Activo BIT DEFAULT 1
)";
sqlsrv_query($conn, $sqlCreateTable);

// Procesar edición/eliminación (solo administradores)
if ($esAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'guardar') {
            $titulo = $_POST['titulo'] ?? '';
            $contenido = $_POST['contenido'] ?? '';
            $imagenes = '';

            // Procesar carga de imágenes
            if (!empty($_FILES['imagenes']['name'][0])) {
                $uploadDir = '../uploads/comunicados/';
                $imagenesArray = [];

                foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name)) {
                        $fileName = time() . '_' . $key . '_' . basename($_FILES['imagenes']['name'][$key]);
                        $targetFile = $uploadDir . $fileName;

                        if (move_uploaded_file($tmp_name, $targetFile)) {
                            $imagenesArray[] = $fileName;
                        }
                    }
                }

                if (!empty($imagenesArray)) {
                    $imagenes = implode(',', $imagenesArray);
                }
            }

            if (isset($_POST['id_comunicado']) && !empty($_POST['id_comunicado'])) {
                // Actualizar
                $id = $_POST['id_comunicado'];
                if (!empty($imagenes)) {
                    $sql = "UPDATE Comunicados SET Titulo = ?, Contenido = ?, Imagenes = ? WHERE IdComunicado = ?";
                    $params = array($titulo, $contenido, $imagenes, $id);
                } else {
                    $sql = "UPDATE Comunicados SET Titulo = ?, Contenido = ? WHERE IdComunicado = ?";
                    $params = array($titulo, $contenido, $id);
                }
            } else {
                // Insertar nuevo
                $sql = "INSERT INTO Comunicados (Titulo, Contenido, Imagenes) VALUES (?, ?, ?)";
                $params = array($titulo, $contenido, $imagenes);
            }

            sqlsrv_query($conn, $sql, $params);
            header('Location: comunicados.php');
            exit();
        } elseif ($_POST['accion'] === 'eliminar') {
            $id = $_POST['id_comunicado'];
            $sql = "UPDATE Comunicados SET Activo = 0 WHERE IdComunicado = ?";
            sqlsrv_query($conn, $sql, array($id));
            header('Location: comunicados.php');
            exit();
        }
    }
}

// Obtener comunicados activos
$sql = "SELECT * FROM Comunicados WHERE Activo = 1 ORDER BY FechaPublicacion DESC";
$stmt = sqlsrv_query($conn, $sql);
?&gt;

&lt;div class="container"&gt;
    &lt;div class="section-header"&gt;
        &lt;h1&gt;📢 Comunicados&lt;/h1&gt;
        &lt;p&gt;Mantente informado sobre los últimos anuncios y comunicados de la empresa&lt;/p&gt;
    &lt;/div&gt;

    &lt;?php if ($esAdmin): ?&gt;
    &lt;div class="content-box"&gt;
        &lt;button class="btn btn-primary" onclick="abrirModalComunicado()"&gt;+ Nuevo Comunicado&lt;/button&gt;
    &lt;/div&gt;
    &lt;?php endif; ?&gt;

    &lt;div class="comunicados-lista"&gt;
        &lt;?php
        if ($stmt === false) {
            echo '&lt;div class="alert alert-error"&gt;Error al cargar comunicados&lt;/div&gt;';
        } else {
            $hayComunicados = false;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $hayComunicados = true;
                $fecha = $row['FechaPublicacion'];
                $fechaFormateada = $fecha ? $fecha-&gt;format('d/m/Y H:i') : '';
        ?&gt;
        &lt;div class="comunicado-item"&gt;
            &lt;div class="comunicado-date"&gt;&lt;?php echo htmlspecialchars($fechaFormateada); ?&gt;&lt;/div&gt;
            &lt;h2&gt;&lt;?php echo htmlspecialchars($row['Titulo']); ?&gt;&lt;/h2&gt;

            &lt;?php if (!empty($row['Imagenes'])): ?&gt;
                &lt;div class="comunicado-images"&gt;
                    &lt;?php
                    $imagenes = explode(',', $row['Imagenes']);
                    foreach ($imagenes as $imagen):
                    ?&gt;
                        &lt;img src="/uploads/comunicados/&lt;?php echo htmlspecialchars($imagen); ?&gt;" alt="Imagen comunicado"&gt;
                    &lt;?php endforeach; ?&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;div class="comunicado-content"&gt;
                &lt;?php echo nl2br(htmlspecialchars($row['Contenido'])); ?&gt;
            &lt;/div&gt;

            &lt;?php if ($esAdmin): ?&gt;
            &lt;div style="margin-top: 1rem; display: flex; gap: 0.5rem;"&gt;
                &lt;button class="btn btn-sm btn-secondary" onclick='editarComunicado(&lt;?php echo json_encode($row); ?&gt;)'&gt;Editar&lt;/button&gt;
                &lt;form method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar este comunicado?')"&gt;
                    &lt;input type="hidden" name="accion" value="eliminar"&gt;
                    &lt;input type="hidden" name="id_comunicado" value="&lt;?php echo $row['IdComunicado']; ?&gt;"&gt;
                    &lt;button type="submit" class="btn btn-sm btn-danger"&gt;Eliminar&lt;/button&gt;
                &lt;/form&gt;
            &lt;/div&gt;
            &lt;?php endif; ?&gt;
        &lt;/div&gt;
        &lt;?php
            }

            if (!$hayComunicados) {
                echo '&lt;div class="alert alert-info"&gt;No hay comunicados disponibles en este momento.&lt;/div&gt;';
            }

            sqlsrv_free_stmt($stmt);
        }
        ?&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;?php if ($esAdmin): ?&gt;
&lt;!-- Modal para crear/editar comunicado --&gt;
&lt;div id="modalComunicado" class="modal"&gt;
    &lt;div class="modal-content"&gt;
        &lt;div class="modal-header"&gt;
            &lt;h2 id="modalTitulo"&gt;Nuevo Comunicado&lt;/h2&gt;
            &lt;button class="modal-close" onclick="cerrarModalComunicado()"&gt;&amp;times;&lt;/button&gt;
        &lt;/div&gt;
        &lt;form method="POST" enctype="multipart/form-data"&gt;
            &lt;input type="hidden" name="accion" value="guardar"&gt;
            &lt;input type="hidden" name="id_comunicado" id="id_comunicado"&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="titulo"&gt;Título del Comunicado&lt;/label&gt;
                &lt;input type="text" id="titulo" name="titulo" required&gt;
            &lt;/div&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="contenido"&gt;Contenido&lt;/label&gt;
                &lt;textarea id="contenido" name="contenido" rows="8"&gt;&lt;/textarea&gt;
            &lt;/div&gt;

            &lt;div class="form-group"&gt;
                &lt;label for="imagenes"&gt;Imágenes (puede seleccionar varias)&lt;/label&gt;
                &lt;input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple&gt;
            &lt;/div&gt;

            &lt;div style="display: flex; gap: 1rem; justify-content: flex-end;"&gt;
                &lt;button type="button" class="btn btn-secondary" onclick="cerrarModalComunicado()"&gt;Cancelar&lt;/button&gt;
                &lt;button type="submit" class="btn btn-primary"&gt;Guardar&lt;/button&gt;
            &lt;/div&gt;
        &lt;/form&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;?php endif; ?&gt;

&lt;?php include '../includes/footer.php'; ?&gt;
