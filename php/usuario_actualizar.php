<?php

require_once '../inc/session_start.php';
require_once './main.php';

$id = limpiar_cadena($_POST['usuario_id']);

// Verificar que el usuario exista
$check_user = conexion();
$check_user = $check_user->query("SELECT * FROM usuario WHERE usuario_id = '$id'");

if ($check_user->rowCount() <= 0) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    El usuario no existe en el sistema.
    </div>
  ';
} else {
  $datos = $check_user->fetch();

  # Almacenar datos del formulario #
}

$check_user = null;

$admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
$admin_clave = limpiar_cadena($_POST['administrador_clave']);

// Verificar campos obligatorios 
if ($admin_usuario == "" || $admin_clave == "") {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    Su Usuario y Clave son obligatorios
    </div>
  ';
  exit();
}

// Verificar integridad de los datos 
if (verificar_datos("^[a-zA-Z0-9]{4,20}$", $admin_usuario)) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    Su USUARIO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-Z0-9$@.-]{7,100}$", $admin_clave)) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    Su CLAVE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

// Verificar que el usuario admin exista
$check_admin = conexion();
$check_admin = $check_admin->query("SELECT usuario_usuario, usuario_clave 
    FROM usuario WHERE usuario_usuario = '$admin_usuario' AND usuario_id = '" . $_SESSION['id'] . "'");

if ($check_admin->rowCount() == 1) {
  $check_admin = $check_admin->fetch();

  # Verificar usuario y clave #
  if ($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])) {
    echo '
      <div class="notification is-danger is-light mb-6 mt-6">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      USUARIO o CLAVE no son correctos
      </div>
    ';
    exit();
  }
} else {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    USUARIO o CLAVE no son correctos
    </div>
  ';
  exit();
}
$check_admin = null;

// Almacenar datos del formulario 
$nombre = limpiar_cadena($_POST['usuario_nombre']);
$apellido = limpiar_cadena($_POST['usuario_apellido']);

$usuario = limpiar_cadena($_POST['usuario_usuario']);
$email = limpiar_cadena($_POST['usuario_email']);

$clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
$clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

// Verificar campos obligatorios 
if ($nombre == "" || $apellido == "" || $usuario == "") {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrió un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>
  ';
  exit();
}

// Verificar integridad de los datos 
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

// verificar el email
if ($email != "" && $email != $datos['usuario_email']) {
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

// Verificar usuario
if ($usuario != $datos['usuario_usuario']) {
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
}

// Verificar las claves 
if ($clave_1 != "" || $clave_2 != "") {
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
  } else {
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
  }
} else {
  $clave = $datos['usuario_clave'];
}

// Actualizar datos del usuario 
$actualizar_usuario = conexion();
$actualizar_usuario = $actualizar_usuario->prepare("UPDATE usuario SET 
    usuario_nombre = :nombre,
    usuario_apellido = :apellido,
    usuario_usuario = :usuario,
    usuario_clave = :clave,
    usuario_email = :email
    WHERE usuario_id = :id");

$actualizar_usuario->bindParam(':id', $id);
$actualizar_usuario->bindParam(':nombre', $nombre);
$actualizar_usuario->bindParam(':apellido', $apellido);
$actualizar_usuario->bindParam(':usuario', $usuario);
$actualizar_usuario->bindParam(':clave', $clave);
$actualizar_usuario->bindParam(':email', $email);

if ($actualizar_usuario->execute()) {

  echo '
    <div class="notification is-info is-light">
      <strong>¡USUARIO actualizado!</strong><br>
      Los datos del USUARIO se actualizaron correctamente
    </div>
  ';
} else {

  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No se pudo actualizar los datos del USUARIO,
      por favor intente nuevamente
    </div>
  ';
}

$actualizar_usuario = null;
