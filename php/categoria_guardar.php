<?php

require_once './main.php';

// Almacenar datos del formulario 
$nombre = limpiar_cadena($_POST['categoria_nombre']);
$ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

// Verificar campos obligatorios 
if ($nombre == "") {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>
  ';
  exit();
}

// Verificar integridad de los datos 
if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}$", $nombre)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if ($ubicacion != "") {
  if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}$", $ubicacion)) {
    echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      La UBICACION no coincide con el formato solicitado
    </div>
  ';
    exit();
  }
}

// Verificar si el nombre de la categoria existe
$check_categoria = conexion();
$check_categoria = $check_categoria->query("SELECT categoria_nombre FROM categoria 
  WHERE categoria_nombre = '$nombre'");

if ($check_categoria->rowCount() > 0) {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    El NOMBRE de la categoria se encuentra registrado, por favor elige otro
  </div>
';
  exit();
}
$check_user = null;

// Guardar la categoria 
$guardar_categoria = conexion();
$guardar_categoria = $guardar_categoria->prepare("INSERT INTO categoria 
  VALUES(NULL, :nombre, :ubicacion)");

$guardar_categoria->bindParam(':nombre', $nombre);
$guardar_categoria->bindParam(':ubicacion', $ubicacion);

if ($guardar_categoria->execute()) {
  echo '
  <div class="notification is-info is-light">
    <strong>¡Categoria registrada!</strong><br>
    La categoria se ha registrado con éxito
  </div>
';
} else {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    No se pudo registrar la categoria, por favor intente nuevamente
  </div>
';
}
$guardar_categoria = null;
