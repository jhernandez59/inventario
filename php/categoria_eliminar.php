<?php

$category_id_del = limpiar_cadena($_GET['category_id_del']);

// Verificar la existencia de la categoria
$check_category = conexion();
$check_category = $check_category->query(
  "SELECT categoria_id FROM categoria WHERE categoria_id = '$category_id_del'"
);

if ($check_category->rowCount() == 1) {
  // Verificar que la categoria no tenga productos asociados antes de eliminar categoria
  $check_producto = conexion();
  $check_producto = $check_producto->query(
    "SELECT categoria_id FROM producto WHERE categoria_id = '$category_id_del' LIMIT 1"
  );

  if ($check_producto->rowCount() > 0) {
    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        No podemos eliminar la categoria, tiene productos asociados
      </div>
    ';
  } else {
    // Eliminar categoria
    $eliminar_categoria = conexion();
    $eliminar_categoria = $eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id = :id");
    $eliminar_categoria->execute([":id" => $category_id_del]);

    if ($eliminar_categoria->rowCount() > 0) {
      echo '
        <div class="notification is-info is-light">
          <strong>¡Categoria Eliminada!</strong><br>
          La categoria se ha eliminado con éxito
        </div>
      ';
    } else {
      echo '
        <div class="notification is-danger is-light">
          <strong>¡Ocurrido un error inesperado!</strong><br>
          No se pudo eliminar la categoria, por favor intente nuevamente
        </div>
      ';
    }
    $eliminar_categoria = null;
  }

  $check_producto = null;
} else {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      La CATEGORIA que intenta eliminar no existe
    </div>
  ';
}

$check_category = null;
