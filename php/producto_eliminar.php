<?php

$product_id_del = limpiar_cadena($_GET['product_id_del']);

// Verificar la existencia del producto
$check_product = conexion();
$check_product = $check_product->query(
  "SELECT producto_id FROM producto WHERE producto_id = '$product_id_del'"
);

if ($check_product->rowCount() == 1) {
  $datos = $check_product->fetch();

  // Eliminar producto
  $eliminar_producto = conexion();
  $eliminar_producto = $eliminar_producto->prepare("DELETE FROM producto WHERE producto_id = :id");
  $eliminar_producto->execute([":id" => $product_id_del]);

  if ($eliminar_producto->rowCount() > 0) {
    // Eliminar imagen asociada al producto
    $producto_foto = $datos['producto_foto'];
    if (is_file("./img/producto/" . $producto_foto)) {
      chmod("./img/producto/" . $producto_foto, 0777);
      unlink("./img/producto/" . $producto_foto);
    }

    echo '
      <div class="notification is-info is-light">
        <strong>¡Producto Eliminado!</strong><br>
        El producto se ha eliminado con éxito
      </div>
    ';
  } else {
    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        No se pudo eliminar el producto, por favor intente nuevamente
      </div>
    ';
  }
  $eliminar_producto = null;
} else {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El PRODUCTO que intenta eliminar no existe
    </div>
  ';
}
$check_product = null;
