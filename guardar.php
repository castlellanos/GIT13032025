<?php
$imagenesSubidas = [];

if (!empty($_FILES['imagenes']['name'][0])) {
    $dir = "uploads/";
    if (!is_dir($dir)) mkdir($dir);
    
    foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['imagenes']['error'][$index] === 0) {
            $nombreArchivo = uniqid() . "_" . basename($_FILES['imagenes']['name'][$index]);
            $ruta = $dir . $nombreArchivo;
            move_uploaded_file($tmpName, $ruta);
            $imagenesSubidas[] = $ruta;
        }
    }
}

if (empty($imagenesSubidas)) {
    die("❌ No se subió ninguna imagen válida.");
}

$producto = [
    "id" => uniqid("prod_"),
    "nombre" => $_POST["nombre"],
    "descripcion" => $_POST["descripcion"],
    "costo" => floatval($_POST["costo"]),
    "precio" => floatval($_POST["precio"]),
    "stock" => intval($_POST["stock"]),
    "estado" => "pendiente",
    "imagenes" => $imagenesSubidas
];

$archivo = "productos.json";
$productos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
$productos[] = $producto;
file_put_contents($archivo, json_encode($productos, JSON_PRETTY_PRINT));

header("Location: admin.php");