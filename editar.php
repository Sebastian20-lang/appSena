<?php
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errores = [];

if ($id <= 0) {
    header('Location: index.php?mensaje=' . urlencode('Producto no valido.'));
    exit;
}

$stmt = $pdo->prepare("SELECT id, nombre, descripcion, cantidad, precio FROM Productos WHERE id = :id");
$stmt->execute([':id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: index.php?mensaje=' . urlencode('Producto no encontrado.'));
    exit;
}

$datos = [
    'nombre' => $producto['nombre'],
    'descripcion' => $producto['descripcion'] ?? '',
    'cantidad' => (string) $producto['cantidad'],
    'precio' => (string) $producto['precio'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos['nombre'] = trim($_POST['nombre'] ?? '');
    $datos['descripcion'] = trim($_POST['descripcion'] ?? '');
    $datos['cantidad'] = trim($_POST['cantidad'] ?? '0');
    $datos['precio'] = trim($_POST['precio'] ?? '');

    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }

    if (!filter_var($datos['cantidad'], FILTER_VALIDATE_INT) && $datos['cantidad'] !== '0') {
        $errores[] = 'La cantidad debe ser un numero entero.';
    }

    if (!is_numeric($datos['precio']) || (float) $datos['precio'] < 0) {
        $errores[] = 'El precio debe ser un numero valido.';
    }

    if (empty($errores)) {
        $sql = "UPDATE Productos SET nombre = :nombre, descripcion = :descripcion, cantidad = :cantidad, precio = :precio WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] !== '' ? $datos['descripcion'] : null,
            ':cantidad' => (int) $datos['cantidad'],
            ':precio' => (float) $datos['precio'],
        ]);

        header('Location: index.php?mensaje=' . urlencode('Producto actualizado correctamente.'));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <h1>Editar producto #<?= $id ?></h1>
                <a class="button" href="index.php">Volver</a>
            </div>

            <?php if ($errores): ?>
                <div class="alert error">
                    <?php foreach ($errores as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <label>
                    Nombre
                    <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required>
                </label>

                <label>
                    Descripcion
                    <textarea name="descripcion" rows="4"><?= htmlspecialchars($datos['descripcion']) ?></textarea>
                </label>

                <label>
                    Cantidad
                    <input type="number" name="cantidad" min="0" value="<?= htmlspecialchars($datos['cantidad']) ?>" required>
                </label>

                <label>
                    Precio
                    <input type="number" name="precio" min="0" step="0.01" value="<?= htmlspecialchars($datos['precio']) ?>" required>
                </label>

                <button class="button primary" type="submit">Actualizar producto</button>
            </form>
        </section>
    </main>
</body>
</html>
