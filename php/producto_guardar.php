<?php

require_once "../inc/session_start.php";
require_once "./main.php";

// Almacenar datos del formulario 
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);

$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
// $foto = $_FILES['producto_foto'];
$categoria = limpiar_cadena($_POST['producto_categoria']);

// Verificar campos obligatorios
if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "") {
  echo '
    <div class="notification is-danger is-light"> 
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>  
  ';
  exit();
}

// Verificar integridad de los datos
if (verificar_datos("^[a-zA-Z0-9- ]{1,70}$", $codigo)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El CODIGO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}$", $nombre)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[0-9.]{1,25}$", $precio)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El PRECIO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[0-9]{1,25}$", $stock)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El STOCK no coincide con el formato solicitado
    </div>
  ';
  exit();
}

// Verificar que el codigo no exista
$check_codigo = conexion();
$check_codigo = $check_codigo->query("SELECT * FROM producto WHERE producto_codigo = '$codigo'");
if ($check_codigo->rowCount() > 0) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El CODIGO ya se encuentra registrado, por favor intente nuevamente
    </div>
  ';
  exit();
}
$check_codigo = null;

// Verificar que el nombre no exista
$check_nombre = conexion();
$check_nombre = $check_nombre->query("SELECT * FROM producto WHERE producto_nombre = '$nombre'");
if ($check_nombre->rowCount() > 0) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE ya se encuentra registrado, por favor intente nuevamente
    </div>
  ';
  exit();
}
$check_nombre = null;

// Verificar que la categoria exista
$check_categoria = conexion();
$check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$categoria'");
if ($check_categoria->rowCount() <= 0) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      La CATEGORIA no existe, por favor intente nuevamente
    </div>
  ';
  exit();
}
$check_categoria = null;

/* --- Subir imagen --- */
// Directorio donde se guardaran las imágenes
$img_dir = "../img/producto/";

// Comprobar si el archivo es una imagen
if ($_FILES['producto_foto']['name'] != "" && $_FILES['producto_foto']['size'] > 0) {

  // verifica si existe el directorio de imágenes
  if (!file_exists($img_dir)) {
    if (!mkdir($img_dir, 0777, true)) {
      echo '
        <div class="notification is-danger is-light">
          <strong>¡Ocurrido un error inesperado!</strong><br>
          No se pudo crear el directorio de imágenes, por favor intente nuevamente
        </div>
      ';
      exit();
    }
  }

  // Verificar el formato de la imagen
  if (
    mime_content_type($_FILES['producto_foto']['tmp_name'])   != "image/jpeg"
    && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpg"
    && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png"
  ) {

    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        La imagen debe ser JPG, JPEG o PNG
      </div>
    ';
    exit();
  }

  // Verificar el tamaño de la imagen en bytes(KB) > 3MB
  if ($_FILES['producto_foto']['size'] / 1024 > (3 * 1024)) {
    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        La imagen debe pesar menos de 3MB
      </div>
    ';
    exit();
  }

  // Extension de la imagen
  switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
    case "image/jpeg":
      $img_ext = ".jpg";
      break;
    case "image/jpg":
      $img_ext = ".jpg";
      break;
    case "image/png":
      $img_ext = ".png";
      break;
  }

  // permiso de escritura
  if (!is_writable($img_dir)) {
    chmod($img_dir, 0777);
  }

  // La imagen se renombrara con el nombre del producto
  $img_nombre = renombrar_fotos($nombre);
  $foto = $img_nombre . $img_ext;

  // Subir la imagen al servidor
  if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        No se pudo subir la imagen, por favor intente nuevamente
      </div>
    ';
    exit();
  }
} else {
  $foto = "";
}

// Guardar el producto
$guardar_producto = conexion();
$guardar_producto = $guardar_producto->prepare(
  "INSERT INTO producto(producto_codigo, producto_nombre, producto_precio, producto_stock, 
    producto_foto, categoria_id, usuario_id) 
  VALUES(:codigo, :nombre, :precio, :stock, :foto, :categoria, :usuario)"
);

$marcadores = [
  ":codigo" => $codigo,
  ":nombre" => $nombre,
  ":precio" => $precio,
  ":stock" => $stock,
  ":foto" => $foto,
  ":categoria" => $categoria,
  ":usuario" => $_SESSION['id']
];

$guardar_producto->execute($marcadores);
if ($guardar_producto->rowCount() == 1) {
  echo '
    <div class="notification is-info is-light">
      <strong>¡Producto registrado!</strong><br>
      El producto se ha registrado con éxito
    </div>
  ';
} else {
  // No se pudo registrar el producto

  // eliminar la imagen
  if (is_file($img_dir . $foto)) {
    chmod($img_dir . $foto, 0777);
    unlink($img_dir . $foto);
  }

  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No se pudo registrar el producto, por favor intente nuevamente
    </div>
  ';
}

$guardar_producto = null;
