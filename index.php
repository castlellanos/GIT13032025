<?php
$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
$publicados = array_filter($productos, fn($p) => $p['estado'] === 'publicado');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Melucci | Galería Artesanal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fdfcf8;
    }
    header {
      text-align: center;
      margin-bottom: 2rem;
    }
    header img {
      max-height: 80px;
      margin-bottom: 10px;
    }
    h1 {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      color: #2c3e50;
    }
    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card img {
      height: 240px;
      object-fit: cover;
      border-bottom: 2px solid #e0e0e0;
    }
    .card-body {
      background: #fff;
      padding: 1rem 1.2rem;
    }
    .card-footer {
      background-color: #f9f9f9;
      border-top: none;
      font-size: 0.9rem;
    }
    .precio {
      color: #1e8449;
      font-weight: bold;
      font-size: 1.1rem;
    }
  </style>
</head>
<body class="container py-4">
  <header>
    <img src="logo.png" alt="Logo Melucci">
    <h1>🌿 Melucci | Galería Artesanal</h1>
    <p class="text-muted">Diseños únicos hechos con amor y detalle</p>
  </header>

  <div class="row">
    <?php foreach ($publicados as $p): ?>
    <div class="col-12 col-md-6 col-lg-4 mb-4">
      <div class="card h-100">
        <img src="<?= $p['imagenes'][0] ?>" class="card-img-top" alt="Producto" data-bs-toggle="modal" data-bs-target="#modal-<?= $p['id'] ?>">

        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
          <p class="card-text"><?= htmlspecialchars($p['descripcion']) ?></p>
        </div>
        <div class="card-footer">
          <div class="precio">$<?= number_format($p['precio'], 0, ',', '.') ?></div>
          <small class="text-muted">Disponible: <?= $p['stock'] ?> unidades</small>
        </div>
      </div>
    </div>

    <!-- Modal para galería -->
    <div class="modal fade" id="modal-<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4">
          <div class="modal-header">
            <h5 class="modal-title"><?= htmlspecialchars($p['nombre']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body text-center">
            <img id="modal-img-<?= $p['id'] ?>" src="<?= $p['imagenes'][0] ?>" style="max-width:100%; height:auto; cursor: zoom-in;" onclick="this.style.transform = (this.style.transform === 'scale(2)' ? 'scale(1)' : 'scale(2)')" />
            <?php if (count($p['imagenes']) > 1): ?>
              <div class="mt-3 d-flex justify-content-center flex-wrap">
                <?php foreach ($p['imagenes'] as $img): ?>
                  <img src="<?= $img ?>" style="width:60px; height:60px; object-fit:cover; margin:5px; border-radius:6px; border:1px solid #ccc; cursor:pointer;" onclick="document.getElementById('modal-img-<?= $p['id'] ?>').src=this.src">
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
