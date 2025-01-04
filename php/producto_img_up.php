<?php
/* Conexion y Verificación del producto */
require_once './main.php';

$producto_id = limpiar_cadena($_POST['img_up_id']);

// Verificar que el producto existe
$check_product = conexion();
$check_product = $check_product->query("SELECT * FROM producto WHERE producto_id = '$producto_id'");

if ($check_product->rowCount() == 1) {
  $datos = $check_product->fetch();
} else {
  $check_product = null;
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    La Imagen del Producto no existe en el sistema.
    </div>
  ';
  exit();
}
$check_product = null;

/* Directorio y Comprobación de la imagen */
// Directorio donde se guardaran las imágenes
$img_dir = "../img/producto/";

// Comprobar si el archivo es una imagen
if ($_FILES['producto_foto']['name'] == "" || $_FILES['producto_foto']['size'] == 0) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    No ha seleccionado ninguna imagen, por favor intente nuevamente.
    </div>
  ';
  exit();
}

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

// permiso de escritura
if (!is_writable($img_dir)) {
  chmod($img_dir, 0777);
}

/* Verificar el formato y tamaño de la imagen */
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

/* Renombrar subir y eliminar la imagen */
// La imagen se renombrara con el nombre del producto
$img_nombre = renombrar_fotos($datos['producto_nombre']);
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

// eliminar la imagen anterior si existe
$img_path = $img_dir . $datos['producto_foto'];
if (is_file($img_path) && $datos['producto_foto'] != $foto) {
  unlink($img_path);
}

/* Actualizar la Imagen del producto en la base de datos */
// Actualizar la Imagen del producto con ""
$actualizar_producto = conexion();
$actualizar_producto = $actualizar_producto->prepare(
  "UPDATE producto SET 
    producto_foto=:foto 
    WHERE producto_id = :id"
);

$marcadores = [
  ":foto" => $foto,
  ":id" => $producto_id
];

$actualizar_producto->execute($marcadores);
if ($actualizar_producto->rowCount() == 1) {
  echo '
    <div class="notification is-info is-light">
      <strong>¡IMAGEN o FOTO actualizada!</strong><br>
      La imagen del producto se ha actualizado con éxito.
      Pulse en el botón Aceptar para recargar la pagina con los cambios.
    </div>
    <p class="has-text-centered pt-5 pb-5">
      <a href="index.php?vista=product_img&product_id_up=' . $producto_id . '" class="button is-link is-rounded is-small mt-4 mb-4">Aceptar</a>
    </p>
  ';
} else {
  // No se pudo subir la imagen
  if (is_file($img_dir . $foto)) {
    chmod($img_dir . $foto, 0777);
    unlink($img_dir . $foto);
  }
  echo '
    <div class="notification is-warning is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No podemos subir la imagen, por favor intente nuevamente
    </div>
  ';
}
$actualizar_producto = null;
