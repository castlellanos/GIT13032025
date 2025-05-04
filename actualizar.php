<?php
$id = $_POST['id'] ?? '';
$accion = $_POST['accion'] ?? '';

$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];

foreach ($productos as &$producto) {
    if ($producto['id'] === $id) {
        if ($accion === 'estado') {
            $producto['estado'] = $producto['estado'] === 'publicado' ? 'pendiente' : 'publicado';

        } elseif ($accion === 'eliminar') {
            $producto = null;

        } elseif ($accion === 'editar') {
            $producto['nombre'] = $_POST['nombre'];
            $producto['descripcion'] = $_POST['descripcion'];
            $producto['costo'] = floatval($_POST['costo']);
            $producto['precio'] = floatval($_POST['precio']);
            $producto['stock'] = intval($_POST['stock']);

            // Eliminar imágenes seleccionadas
            if (!empty($_POST['eliminar_imagenes'])) {
                foreach ($_POST['eliminar_imagenes'] as $indice) {
                    if (isset($producto['imagenes'][(int)$indice])) {
                        unset($producto['imagenes'][(int)$indice]);
                    }
                }
                // Reindexar el array
                $producto['imagenes'] = array_values($producto['imagenes']);
            }

            // Reemplazar por nuevas imágenes si se cargan
            if (isset($_FILES['imagen']) && is_array($_FILES['imagen']['tmp_name'])) {
                $imagenesNuevas = [];
                $dir = "uploads/";
                if (!is_dir($dir)) mkdir($dir);
                foreach ($_FILES['imagen']['tmp_name'] as $i => $tmpName) {
                    if ($_FILES['imagen']['error'][$i] === 0) {
                        $nombreArchivo = uniqid() . "_" . basename($_FILES['imagen']['name'][$i]);
                        $ruta = $dir . $nombreArchivo;
                        move_uploaded_file($tmpName, $ruta);
                        $imagenesNuevas[] = $ruta;
                    }
                }
                if (!empty($imagenesNuevas)) {
                    $producto['imagenes'] = $imagenesNuevas;
                }
            }
        }
        break;
    }
}

$productos = array_filter($productos);
file_put_contents($archivo, json_encode(array_values($productos), JSON_PRETTY_PRINT));

header("Location: admin.php");
