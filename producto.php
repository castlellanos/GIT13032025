<?php
$id = $_GET['id'] ?? '';
$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];

$producto = null;
foreach ($productos as $p) {
    if ($p['id'] === $id) {
        $producto = $p;
        break;
    }
}

if (!$producto) {
    echo "<h3 style='color:red;'>Producto no encontrado</h3>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['nombre']) ?> - Melucci</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .galeria img {
      height: 100px;
      width: 100px;
      object-fit: cover;
      margin: 6px;
      cursor: pointer;
      border-radius: 10px;
      border: 1px solid #ccc;
      transition: transform 0.3s;
    }
    .galeria img:hover {
      transform: scale(1.1);
    }
    .preview-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      margin: 30px 0;
    }
    .preview-frame {
      position: relative;
      background: linear-gradient(135deg, #f8f8f8, #e0e0e0);
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      border: 3px double #888;
      padding: 10px;
    }
    .preview {
      width: 350px;
      height: 350px;
      object-fit: contain;
      border-radius: 14px;
      border: 2px solid #bbb;
    }
    .zoom-wrapper {
      position: relative;
      display: inline-block;
    }
    .zoom-lens {
      position: absolute;
      border: 2px solid #999;
      width: 100px;
      height: 100px;
      opacity: 0.4;
      background-color: #ccc;
      pointer-events: none;
    }
    .zoom-result {
      border: 2px solid #ccc;
      width: 300px;
      height: 300px;
      overflow: hidden;
      position: relative;
    }
    .zoom-result img {
      position: absolute;
    }
  </style>
</head>
<body class="container mt-4">
  <h2 class="text-success">🌿 <?= htmlspecialchars($producto['nombre']) ?></h2>
  <p><strong>Código:</strong> <?= $producto['id'] ?></p>
  <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion']) ?></p>
  <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 0, ',', '.') ?></p>
  <p><strong>Stock disponible:</strong> <?= $producto['stock'] ?> unidades</p>

  <div class="preview-container">
    <div class="preview-frame zoom-wrapper" id="zoomWrapper">
      <img src="<?= $producto['imagenes'][0] ?>" class="preview" id="imgPrincipal" alt="Imagen principal">
      <div class="zoom-lens" id="zoomLens"></div>
    </div>
    <div class="zoom-result" id="zoomResult"><img src="<?= $producto['imagenes'][0] ?>" id="zoomedImg"></div>
  </div>

  <div class="galeria d-flex flex-wrap justify-content-center">
    <?php foreach ($producto['imagenes'] as $img): ?>
      <img src="<?= $img ?>" onclick="cambiarImagen(this.src)">
    <?php endforeach; ?>
  </div>

  <a href="index.php" class="btn btn-secondary mt-4">← Volver al catálogo</a>

  <script>
    function cambiarImagen(src) {
      document.getElementById('imgPrincipal').src = src;
      document.getElementById('zoomedImg').src = src;
    }

    const lens = document.getElementById("zoomLens");
    const result = document.getElementById("zoomResult");
    const img = document.getElementById("imgPrincipal");
    const zoomed = document.getElementById("zoomedImg");

    result.style.display = "block";

    document.getElementById("zoomWrapper").addEventListener("mousemove", moveLens);
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);

    function moveLens(e) {
      const pos = getCursorPos(e);
      let x = pos.x - lens.offsetWidth / 2;
      let y = pos.y - lens.offsetHeight / 2;

      x = Math.max(0, Math.min(x, img.width - lens.offsetWidth));
      y = Math.max(0, Math.min(y, img.height - lens.offsetHeight));

      lens.style.left = x + "px";
      lens.style.top = y + "px";

      const cx = result.offsetWidth / lens.offsetWidth;
      const cy = result.offsetHeight / lens.offsetHeight;

      zoomed.style.width = img.width * cx + "px";
      zoomed.style.height = img.height * cy + "px";
      zoomed.style.left = -x * cx + "px";
      zoomed.style.top = -y * cy + "px";
    }

    function getCursorPos(e) {
      const rect = img.getBoundingClientRect();
      return {
        x: e.pageX - rect.left - window.pageXOffset,
        y: e.pageY - rect.top - window.pageYOffset
      };
    }
  </script>
</body>
</html>
