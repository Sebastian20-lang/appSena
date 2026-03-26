<?php
// Quita el "tcp:" de aquí, déjalo solo como el host
$server = "tinoco-mipresario.database.windows.net,1433"; 
$database = "Crudimplementrio";
$user = "Landaa";
$pass = "SenatiETI@2026";

try {
    // La cadena correcta para el driver sqlsrv es "server" (o "Server")
    $pdo = new PDO("sqlsrv:server=$server;Database=$database", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "¡Conexión exitosa a Azure!";
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}