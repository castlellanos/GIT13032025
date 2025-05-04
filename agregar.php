<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar producto - Melucci</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>➕ Agregar Nuevo Producto</h3>
    <form action="guardar.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Imágenes (.jpg/.png)</label>
            <input type="file" name="imagenes[]" accept="image/*" multiple class="form-control" required>
            <small class="text-muted">Puedes seleccionar varias imágenes (máximo 5 sugerido)</small>
        </div>
        <div class="mb-3">
            <label>Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Costo de adquisición</label>
            <input type="number" name="costo" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Precio de venta</label>
            <input type="number" name="precio" step="0.01" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stock inicial</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <button class="btn btn-success">Guardar</button>
        <a href="admin.php" class="btn btn-secondary">Volver al panel</a>
    </form>
</body>
</html>