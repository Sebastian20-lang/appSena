<?php
require_once __DIR__ . '/config/database.php';

$mensaje = $_GET['mensaje'] ?? null;
$error = null;
$productos = [];

try {
    $stmt = $pdo->query("SELECT id, nombre, descripcion, cantidad, precio, fecha_creacion FROM Productos ORDER BY id DESC");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario CRUD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="container">
        <section class="hero">
            <div>
                <p class="eyebrow">Inventario</p>
                <h1>Control de productos</h1>
                <p class="hero-text">Administra tu stock, precios y descripciones desde una app PHP sencilla conectada a Azure SQL.</p>
            </div>
            <a class="button primary" href="crear.php">Nuevo producto</a>
        </section>

        <?php if ($mensaje): ?>
            <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error">No se pudo consultar la tabla <strong>Productos</strong>: <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-header">
                <h2>Listado general</h2>
                <span><?= count($productos) ?> producto(s)</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="7" class="empty">No hay productos registrados todavia.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= (int) $producto['id'] ?></td>
                                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                    <td><?= htmlspecialchars($producto['descripcion'] ?? '') ?></td>
                                    <td><?= (int) $producto['cantidad'] ?></td>
                                    <td>$<?= number_format((float) $producto['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime((string) $producto['fecha_creacion']))) ?></td>
                                    <td>
                                        <div class="actions">
                                            <a class="button small" href="editar.php?id=<?= (int) $producto['id'] ?>">Editar</a>
                                            <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Eliminar este producto?');">
                                                <input type="hidden" name="id" value="<?= (int) $producto['id'] ?>">
                                                <button class="button danger small" type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
