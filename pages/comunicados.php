<?php
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
?>

<div class="container">
    <div class="section-header">
        <h1>📢 Comunicados</h1>
        <p>Mantente informado sobre los últimos anuncios y comunicados de la empresa</p>
    </div>

    <?php if ($esAdmin): ?>
    <div class="content-box">
        <button class="btn btn-primary" onclick="abrirModalComunicado()">+ Nuevo Comunicado</button>
    </div>
    <?php endif; ?>

    <div class="comunicados-lista">
        <?php
        if ($stmt === false) {
            echo '<div class="alert alert-error">Error al cargar comunicados</div>';
        } else {
            $hayComunicados = false;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $hayComunicados = true;
                $fecha = $row['FechaPublicacion'];
                $fechaFormateada = $fecha ? $fecha->format('d/m/Y H:i') : '';
        ?>
        <div class="comunicado-item">
            <div class="comunicado-date"><?php echo htmlspecialchars($fechaFormateada); ?></div>
            <h2><?php echo htmlspecialchars($row['Titulo']); ?></h2>

            <?php if (!empty($row['Imagenes'])): ?>
                <div class="comunicado-images">
                    <?php
                    $imagenes = explode(',', $row['Imagenes']);
                    foreach ($imagenes as $imagen):
                    ?>
                        <img src="/uploads/comunicados/<?php echo htmlspecialchars($imagen); ?>" alt="Imagen comunicado">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="comunicado-content">
                <?php echo nl2br(htmlspecialchars($row['Contenido'])); ?>
            </div>

            <?php if ($esAdmin): ?>
            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button class="btn btn-sm btn-secondary" onclick='editarComunicado(<?php echo json_encode($row); ?>)'>Editar</button>
                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar este comunicado?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_comunicado" value="<?php echo $row['IdComunicado']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php
            }

            if (!$hayComunicados) {
                echo '<div class="alert alert-info">No hay comunicados disponibles en este momento.</div>';
            }

            sqlsrv_free_stmt($stmt);
        }
        ?>
    </div>
</div>

<?php if ($esAdmin): ?>
<!-- Modal para crear/editar comunicado -->
<div id="modalComunicado" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo">Nuevo Comunicado</h2>
            <button class="modal-close" onclick="cerrarModalComunicado()">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="guardar">
            <input type="hidden" name="id_comunicado" id="id_comunicado">

            <div class="form-group">
                <label for="titulo">Título del Comunicado</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="contenido">Contenido</label>
                <textarea id="contenido" name="contenido" rows="8"></textarea>
            </div>

            <div class="form-group">
                <label for="imagenes">Imágenes (puede seleccionar varias)</label>
                <input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalComunicado()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
