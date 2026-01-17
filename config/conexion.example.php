<?php
// ============================================
// ARCHIVO DE EJEMPLO - DATOS FICTICIOS
// ============================================
// IMPORTANTE: Este archivo contiene datos de ejemplo únicamente.
// NO subir credenciales reales al repositorio.
//
// INSTRUCCIONES:
// 1. Copiar este archivo como "conexion.php"
// 2. Reemplazar los valores ficticios con tus credenciales reales
// 3. El archivo "conexion.php" está en .gitignore y NO se subirá al repositorio
// ============================================

$serverName = "ejemplo-servidor.local"; // FICTICIO - Reemplazar con tu servidor SQL Server
$connectionOptions = array(
    "Database" => "ejemplo_database", // FICTICIO - Reemplazar con el nombre de tu base de datos
    "Uid" => "usuario_ejemplo", // FICTICIO - Reemplazar con tu usuario de SQL Server
    "PWD" => "contraseña_ejemplo_123" // FICTICIO - Reemplazar con tu contraseña real
);

// Establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
