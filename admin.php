<?php
$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración - Melucci</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 2rem; background-color: #f8f9fa; }
    h2 span { font-weight: bold; color: #28a745; }
    .thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 10px; }
    .table td, .table th { vertical-align: middle; font-size: 0.95rem; }
    .btn-sm { padding: 4px 8px; font-size: 0.85rem; }
    .table-responsive { overflow-x: auto; }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="mb-4 text-center">🛠️ Panel de Administración - <span>Melucci</span></h2>
    <div class="d-flex justify-content-end mb-3">
      <a href="agregar.php" class="btn btn-success">➕ Agregar Nuevo Producto</a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Costo</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $p): ?>
          <tr>
            <td>
              <?php if (!empty($p['imagenes'][0])): ?>
                <img src="<?= $p['imagenes'][0] ?>" class="thumb">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['descripcion']) ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
              <form action="actualizar.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="accion" value="estado">
                <button class="btn btn-sm <?= $p['estado'] === 'publicado' ? 'btn-success' : 'btn-secondary' ?>">
                  <?= ucfirst($p['estado']) ?>
                </button>
              </form>
            </td>
            <td>$<?= number_format($p['costo'], 0, ',', '.') ?></td>
            <td>$<?= number_format($p['precio'], 0, ',', '.') ?></td>
            <td>
              <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
              <form action="actualizar.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <input type="hidden" name="accion" value="eliminar">
                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">🗑</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
