<?php
require_once __DIR__ . '/config/database.php';

$errores = [];
$datos = [
    'nombre' => '',
    'descripcion' => '',
    'cantidad' => '0',
    'precio' => '',
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
        $sql = "INSERT INTO Productos (nombre, descripcion, cantidad, precio) VALUES (:nombre, :descripcion, :cantidad, :precio)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] !== '' ? $datos['descripcion'] : null,
            ':cantidad' => (int) $datos['cantidad'],
            ':precio' => (float) $datos['precio'],
        ]);

        header('Location: index.php?mensaje=' . urlencode('Producto creado correctamente.'));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container narrow">
        <section class="panel">
            <div class="panel-header">
                <h1>Registrar producto</h1>
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

                <button class="button primary" type="submit">Guardar producto</button>
            </form>
        </section>
    </main>
</body>
</html>
