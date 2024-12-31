<?php

require_once './main.php';

$id = limpiar_cadena($_POST['categoria_id']);

// Verificar que la categoria existe
$check_category = conexion();
$check_category = $check_category->query("SELECT * FROM categoria WHERE categoria_id = '$id'");

if ($check_category->rowCount() <= 0) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    La CATEGORIA no existe en el sistema.
    </div>
  ';
  exit();
} else {
  $datos = $check_category->fetch();
}
$check_category = null;

// Almacenar datos del formulario 
$nombre = limpiar_cadena($_POST['categoria_nombre']);
$ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

// Verificar campos obligatorios 
if ($nombre == "") {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El campo de la Categoria es obligatorio
    </div>
  ';
  exit();
}

// Verificar integridad de los datos
if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}$", $nombre)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE de la categoria no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if ($ubicacion != "") {
  if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}$", $ubicacion)) {
    echo '  
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        La UBICACION de la categoria no coincide con el formato solicitado
      </div>
    ';
    exit();
  }
}

// Verificar nombre de la categoria (no se pueden repetir)
if ($datos['categoria_nombre'] != $nombre) {
  $check_nombre = conexion();
  $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre = '$nombre' AND categoria_id != $id");
  if ($check_nombre->rowCount() > 0) {
    echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE de la categoria ya se encuentra registrado, por favor elige otro
    </div>
  ';
    exit();
  }
  $check_nombre = null;
}

// Actualizar datos de la categoria 
$actualizar_categoria = conexion();
$actualizar_categoria = $actualizar_categoria->prepare("UPDATE categoria SET 
    categoria_nombre = :nombre,
    categoria_ubicacion = :ubicacion
    WHERE categoria_id = :id");

$actualizar_categoria->bindParam(':id', $id);
$actualizar_categoria->bindParam(':nombre', $nombre);
$actualizar_categoria->bindParam(':ubicacion', $ubicacion);

if ($actualizar_categoria->execute()) {
  echo '
    <div class="notification is-info is-light">
      <strong>¡CATEGORIA ACTUALIZADA!</strong><br>
      Los datos de la CATEGORIA se actualizaron correctamente
    </div>
  ';
} else {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No se pudo actualizar la CATEGORIA,
      por favor intente nuevamente
    </div>
  ';
}

$actualizar_categoria = null;
