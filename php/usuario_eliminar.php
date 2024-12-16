<?php

$user_id_del = limpiar_cadena($_GET['user_id_del']);

// Verificar la existencia del usuario
$check_user = conexion();
$check_user = $check_user->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$user_id_del'");

if ($check_user->rowCount() == 1) {
  // Verificar la existencia del producto
  $check_producto = conexion();
  $check_producto = $check_producto->query("SELECT usuario_id FROM producto 
          WHERE usuario_id = '$user_id_del' LIMIT 1");

  if ($check_producto->rowCount() > 0) {
    echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No podemos eliminar el usuario, tiene productos asociados
    </div>
  ';
  } else {
    // Eliminar usuario
    $eliminar_usuario = conexion();
    $eliminar_usuario = $eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id = :id");
    $eliminar_usuario->execute([":id" => $user_id_del]);

    if ($eliminar_usuario->rowCount() > 0) {
      echo '
    <div class="notification is-info is-light">
      <strong>¡Usuario Eliminado!</strong><br>
      El usuario se ha eliminado con éxito
    </div>
  ';
    } else {
      echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        No se pudo eliminar el usuario, por favor intente nuevamente
      </div>
    ';
    }
    $eliminar_usuario = null;
  }

  $check_producto = null;
} else {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No se pudo eliminar el usuario
    </div>
  ';
}

$check_user = null;
