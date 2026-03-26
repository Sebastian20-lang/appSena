<?php

$server = "tcp:tinoco-mipresario.database.windows.net,1433";
$database = "Crudimplementrio";
$user = "Landaa";
$pass = "{your_password_here}";

try {
    $pdo = new PDO("sqlsrv:server=$server;Database=$database", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}
