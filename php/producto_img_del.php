<?php
require_once './main.php';

$producto_id = limpiar_cadena($_POST['img_del_id']);

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

$img_dir = "../img/producto/";
$img = $img_dir . $datos['producto_foto'];
if (is_file($img)) {
  if (!unlink($img)) {
    echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    Error al intentar eliminar la Imagen del Producto, por favor intente nuevamente.
    </div>
  ';
    exit();
  }
}

// Actualizar la Imagen del producto con ""
$actualizar_producto = conexion();
$actualizar_producto = $actualizar_producto->prepare(
  "UPDATE producto SET 
    producto_foto=:foto 
    WHERE producto_id = :id"
);

$marcadores = [
  ":foto" => "",
  ":id" => $producto_id
];

$actualizar_producto->execute($marcadores);
if ($actualizar_producto->rowCount() == 1) {
  echo '
    <div class="notification is-info is-light">
      <strong>¡IMAGEN o FOTO eliminada!</strong><br>
      La imagen del producto se ha eliminado con éxito.
      Pulse en el botón Aceptar para recargar la pagina con los cambios.
    </div>
    <p class="has-text-centered pt-5 pb-5">
      <a href="index.php?vista=product_img&product_id_up=' . $producto_id . '" class="button is-link is-rounded is-small mt-4 mb-4">Aceptar</a>
    </p>
  ';
} else {
  // No se pudo registrar el producto
  echo '
    <div class="notification is-warning is-light">
      <strong>¡¡IMAGEN o FOTO eliminada!!</strong><br>
      Pulse en el botón Aceptar para recargar la pagina con los cambios.
    </div>

    <p class="has-text-centered pt-5 pb-5">
      <a href="index.php?vista=product_img&product_id_up=' . $producto_id . '" class="button is-link is-rounded is-small mt-4 mb-4">Aceptar</a>
    </p>
  ';
}
$actualizar_producto = null;
