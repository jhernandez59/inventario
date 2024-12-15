<?php

# Almacenar datos del formulario #
$usuario = limpiar_cadena($_POST['login_usuario']);
$clave = limpiar_cadena($_POST['login_clave']);

# Verificar datos requeridos #
if ($usuario == "" || $clave == "") {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrió un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>
  ';
  exit();
}

# Verificar integridad de los datos #
if (verificar_datos("^[a-zA-Z0-9]{4,20}$", $usuario)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El USUARIO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-Z0-9$@.-]{7,100}$", $clave)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      La CLAVE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

# Verificar el usuario #
$check_user = conexion();
$check_user = $check_user->query("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");
if ($check_user->rowCount() > 0) {

  $check_user = $check_user->fetch();

  # Verificar la clave #
  if (($check_user['usuario_usuario'] == $usuario) && password_verify($clave, $check_user['usuario_clave'])) {

    $_SESSION['id'] = $check_user['usuario_id'];
    $_SESSION['nombre'] = $check_user['usuario_nombre'];
    $_SESSION['apellido'] = $check_user['usuario_apellido'];
    $_SESSION['usuario'] = $check_user['usuario_usuario'];

    if (headers_sent()) {
      echo "<script>document.location.href = 'index.php?vista=home';</script>";
    } else {
      header('Location: index.php?vista=home'); // Re-direccionar a la vista home
    }
  } else {
    echo '
        <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado! *** </strong><br>
        USUARIO o CLAVE son incorrectos, por favor verifique
        </div>
      ';
  }
} else {
  echo '
      <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      USUARIO o CLAVE son incorrectos, por favor verifique
      </div>
    ';
}
$check_user = null;
