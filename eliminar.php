<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM Productos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: index.php?mensaje=' . urlencode('Producto eliminado correctamente.'));
    exit;
}

header('Location: index.php?mensaje=' . urlencode('No se pudo eliminar el producto.'));
exit;
