<?php

require_once './main.php';

$id = limpiar_cadena($_POST['producto_id']);

// Verificar que el producto existe
$check_product = conexion();
$check_product = $check_product->query("SELECT * FROM producto WHERE producto_id = '$id'");

if ($check_product->rowCount() <= 0) {
  echo '
    <div class="notification is-danger is-light mb-6 mt-6">
    <strong>¡Ocurrió un error inesperado!</strong><br>
    El PRODUCTO no existe en el sistema.
    </div>
  ';
  exit();
} else {
  $datos = $check_product->fetch();
}
$check_product = null;

// Almacenar datos del formulario 
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);

// Verificar campos obligatorios 
if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "") {
  echo '
    <div class="notification is-danger is-light"> 
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No has llenado todos los campos que son obligatorios
    </div>  
  ';
  exit();
}

// Verificar integridad de los datos
if (verificar_datos("^[a-zA-Z0-9- ]{1,70}$", $codigo)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El CODIGO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}$", $nombre)) {
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[0-9.]{1,25}$", $precio)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El PRECIO no coincide con el formato solicitado
    </div>
  ';
  exit();
}

if (verificar_datos("^[0-9]{1,25}$", $stock)) {
  echo '  
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El STOCK no coincide con el formato solicitado
    </div>
  ';
  exit();
}

// Verificar que el codigo no exista
if ($codigo != $datos['producto_codigo']) {
  $check_codigo = conexion();
  $check_codigo = $check_codigo->query("SELECT * FROM producto WHERE producto_codigo = '$codigo'");
  if ($check_codigo->rowCount() > 0) {
    $check_codigo = null;
    echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        El CODIGO ya se encuentra registrado, por favor intente nuevamente
      </div>
    ';
    exit();
  }
  $check_codigo = null;
}

// Verificar que el nombre no exista
if ($nombre != $datos['producto_nombre']) {
  $check_nombre = conexion();
  $check_nombre = $check_nombre->query("SELECT * FROM producto WHERE producto_nombre = '$nombre'");
  if ($check_nombre->rowCount() > 0) {
    $check_nombre = null;
    echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      El NOMBRE ya se encuentra registrado, por favor intente nuevamente
    </div>
  ';
    exit();
  }
  $check_nombre = null;
}

// Verificar que la categoria exista
if ($categoria != $datos['categoria_id']) {
  $check_categoria = conexion();
  $check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$categoria'");
  if ($check_categoria->rowCount() <= 0) {
    $check_categoria = null;
    echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      La CATEGORIA no existe, por favor intente nuevamente
    </div>
  ';
    exit();
  }
  $check_categoria = null;
}

// Actualizar el producto
$actualizar_producto = conexion();
$actualizar_producto = $actualizar_producto->prepare(
  "UPDATE producto SET 
    producto_codigo=:codigo, 
    producto_nombre=:nombre, 
    producto_precio=:precio, 
    producto_stock=:stock, 
    categoria_id=:categoria 
    WHERE producto_id = :id"
);

$marcadores = [
  ":codigo" => $codigo,
  ":nombre" => $nombre,
  ":precio" => $precio,
  ":stock" => $stock,
  ":categoria" => $categoria,
  ":id" => $id
];

$actualizar_producto->execute($marcadores);
if ($actualizar_producto->rowCount() == 1) {
  echo '
    <div class="notification is-info is-light">
      <strong>¡Producto Actualizado!</strong><br>
      El producto se ha actualizado con éxito
    </div>
  ';
} else {
  // No se pudo registrar el producto
  echo '
    <div class="notification is-danger is-light">
      <strong>¡Ocurrido un error inesperado!</strong><br>
      No se pudo actualizar el producto, por favor intente nuevamente
    </div>
  ';
}
$actualizar_producto = null;
