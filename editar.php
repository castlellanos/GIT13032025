<?php
$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];

$id = $_GET['id'] ?? '';
$producto = null;

foreach ($productos as $p) {
    if ($p['id'] === $id) {
        $producto = $p;
        break;
    }
}

if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto - Melucci</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h3>✏️ Editar Producto</h3>

  <form action="actualizar.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
    <input type="hidden" name="accion" value="editar">

    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Descripción</label>
      <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
    </div>

    <div class="mb-3">
      <label>Costo de adquisición</label>
      <input type="number" name="costo" step="0.01" class="form-control" value="<?= $producto['costo'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Precio de venta</label>
      <input type="number" name="precio" step="0.01" class="form-control" value="<?= $producto['precio'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Stock</label>
      <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
    </div>

    <div class="mb-3">
      <label>Imágenes actuales</label><br>
      <?php foreach ($producto['imagenes'] as $index => $img): ?>
        <div style="display:inline-block; margin:5px; text-align:center;">
          <img src="<?= $img ?>" width="100" style="object-fit:cover;"><br>
          <label>
            <input type="checkbox" name="eliminar_imagenes[]" value="<?= $index ?>"> ❌ Eliminar
          </label>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mb-3">
      <label>¿Deseas reemplazar las imágenes? (opcional)</label>
      <input type="file" name="imagen[]" accept="image/*" class="form-control" multiple>
    </div>

    <button class="btn btn-primary">Guardar cambios</button>
    <a href="admin.php" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>
