<?php
// Ejemplo de configuración de conexión a base de datos
// Copiar este archivo como "conexion.php" y configurar con tus credenciales

$serverName = "TU_SERVIDOR"; // Ejemplo: "KWSERVIFACT" o "localhost\SQLEXPRESS"
$connectionOptions = array(
    "Database" => "TU_BASE_DE_DATOS", // Ejemplo: "KwSin"
    "Uid" => "TU_USUARIO", // Ejemplo: "sa"
    "PWD" => "TU_CONTRASEÑA" // Tu contraseña de SQL Server
);

// Establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
