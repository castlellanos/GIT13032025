// exportar_pdf.php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
$publicados = array_filter($productos, fn($p) => $p['estado'] === 'publicado');

$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
$mpdf->SetTitle('Catálogo de Productos - Melucci');

$logoPath = realpath('logo.png');

$estilo = '<style>
  body {
    font-family: "Segoe UI", sans-serif;
    background-color: #f7f7f7;
    color: #333;
  }
  .titulo {
    text-align: center;
    font-size: 22px;
    color: #1e8449;
    margin: 10px 0 15px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 6px;
    letter-spacing: 0.5px;
  }
  table.producto {
    width: 95%;
    margin: 16px auto;
    border-collapse: collapse;
    border: 2px dashed #81c784;
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(135deg, #ffffff 0%, #f0f5f0 100%);
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    page-break-inside: avoid;
  }
  td.info, td.img {
    vertical-align: middle;
    padding: 16px;
  }
  td.info {
    width: 55%;
  }
  td.img {
    width: 45%;
    text-align: center;
  }
  .info h3 {
    font-size: 20px;
    margin: 6px 0 4px;
    color: #2c3e50;
  }
  .codigo {
    font-size: 11px;
    color: #888;
    margin-bottom: 6px;
  }
  .detalle {
    font-size: 13px;
    margin: 4px 0;
  }
  .miniaturas {
    margin-top: 10px;
  }
  .miniaturas img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-right: 5px;
  }
  .principal-img {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ccc;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  .qr {
    margin-top: 10px;
  }
  .qr a {
    font-size: 11px;
    display: inline-block;
    margin-top: 6px;
    text-decoration: none;
    color: #1e8449;
  }
  .footer {
    font-size: 10px;
    text-align: center;
    margin-top: 30px;
    color: #777;
  }
</style>';

$mpdf->SetHTMLFooter('<div class="footer">Catálogo Melucci | Generado el ' . date("d/m/Y") . ' | melucciartesanal.com</div>');

$mpdf->WriteHTML($estilo);
$mpdf->WriteHTML('  
  <div style="text-align:center; margin-top:10px; margin-bottom:10px;">
    <img src="' . $logoPath . '" height="80" style="margin-bottom:6px;" />
  </div>
  <div class="titulo">🌿 Catálogo de Productos Publicados - <strong>Melucci</strong></div>
');

foreach ($publicados as $p) {
    $html = '<table class="producto"><tr>';

    // Información a la izquierda
    $html .= '<td class="info">';
    $html .= '<div class="codigo">Código: ' . htmlspecialchars($p['id']) . '</div>';
    $html .= '<h3>' . htmlspecialchars($p['nombre']) . '</h3>';
    $html .= '<div class="detalle"><strong>Descripción:</strong> ' . htmlspecialchars($p['descripcion']) . '</div>';
    $html .= '<div class="detalle"><strong>Precio:</strong> $' . number_format($p['precio'], 0, ',', '.') . '</div>';
    $html .= '<div class="detalle"><strong>Stock:</strong> ' . $p['stock'] . ' unidades</div>';

    if (!empty($p['imagenes']) && count($p['imagenes']) > 1) {
        $html .= '<div class="miniaturas">';
        $imagenesMostradas = 0;
        foreach ($p['imagenes'] as $i => $img) {
            if ($i === 0) continue;
            if ($imagenesMostradas >= 3) break;
            if (file_exists($img)) {
                $rutaMini = realpath($img);
                $html .= '<img src="' . $rutaMini . '" />';
                $imagenesMostradas++;
            }
        }
        $html .= '</div>';
    }

    // Código QR por producto
    $linkProducto = 'https://melucciartesanal.com/producto.php?id=' . $p['id'];
    $qr = new QrCode($linkProducto);
    $qr->setSize(100);
    $qr->setMargin(2);
    $writer = new PngWriter();
    $qrPath = __DIR__ . "/temp_qr_{$p['id']}.png";
    $writer->write($qr)->saveToFile($qrPath);
    $html .= '<div class="qr">
                <img src="' . realpath($qrPath) . '" width="80"><br>
                <a href="' . $linkProducto . '">Ver más detalles</a>
              </div>';

    $html .= '</td>';

    // Imagen a la derecha
    $html .= '<td class="img">';
    if (!empty($p['imagenes'][0]) && file_exists($p['imagenes'][0])) {
        $rutaPrincipal = realpath($p['imagenes'][0]);
        $html .= '<img src="' . $rutaPrincipal . '" class="principal-img" />';
    }
    $html .= '</td>';

    $html .= '</tr></table>';
    $mpdf->WriteHTML($html);
}

$mpdf->Output("catalogo_melucci.pdf", "I");
