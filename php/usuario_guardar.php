<?php

require_once './main.php';

# Almacenar datos del formulario #
$nombre = limpiar_cadena($_POST['usuario_nombre']);
$apellido = limpiar_cadena($_POST['usuario_apellido']);

$usuario = limpiar_cadena($_POST['usuario_usuario']);
$email = limpiar_cadena($_POST['usuario_email']);

$clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
$clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

# Verificar campos obligatorios #
if ($nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == "") {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrió un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>
  ';
  exit();
}

# Verificar integridad de los datos #
if (verificar_datos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,32}$", $nombre)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,32}$", $apellido)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El APELLIDO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-Z0-9]{4,16}$", $usuario)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El USUARIO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (
  verificar_datos("^[a-zA-Z0-9$@.-]{7,64}$", $clave_1) ||
  verificar_datos("^[a-zA-Z0-9$@.-]{7,64}$", $clave_2)
) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      Las CLAVES no coincide con el formato solicitado
    </div>
  ';
  exit();
}

# verificar el email #
if ($email != "") {
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $check_email = conexion();
    $check_email = $check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
    if ($check_email->rowCount() > 0) {
      echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        El EMAIL ya se encuentra registrado, por favor elige otro
      </div>
    ';
      exit();
    }
    $check_email = null;
  } else {
    echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El EMAIL no es valido
    </div>
  ';
    exit();
  }
}

# Verificar si el usuario existe #
$check_user = conexion();
$check_user = $check_user->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
if ($check_user->rowCount() > 0) {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    El USUARIO ya se encuentra registrado, por favor elige otro
  </div>
';
  exit();
}
$check_user = null;

# Verificar las claves #
if ($clave_1 != $clave_2) {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    Las CLAVES que has ingresado no son iguales
  </div>
';
  exit();
} else {
  $clave = password_hash($clave_1, PASSWORD_BCRYPT, ['cost' => 10]);
}

# Guardar el usuario #
$guardar_usuario = conexion();
$guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario 
    VALUES(NULL, :nombre, :apellido, :usuario, :clave, :email)");

$guardar_usuario->bindParam(':nombre', $nombre);
$guardar_usuario->bindParam(':apellido', $apellido);
$guardar_usuario->bindParam(':usuario', $usuario);
$guardar_usuario->bindParam(':email', $email);
$guardar_usuario->bindParam(':clave', $clave);

if ($guardar_usuario->execute()) {
  echo '
  <div class="notification is-info is-light">
    <strong>¡USUARIO REGISTRADO!</strong><br>
    El usuario se ha registrado con éxito
  </div>
';
} else {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    No se pudo registrar el usuario, por favor intente nuevamente
  </div>
';
}

$guardar_usuario = null;
