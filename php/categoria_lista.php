<?php

// Establece los limites de los registros a mostrar inicio y total
$inicio = ($pagina > 0) ? ($pagina * $registros) - $registros : 0;
$tabla = "";

if (isset($busqueda) && $busqueda != "") {
  $sql = "SELECT * FROM categoria 
    WHERE (categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%') 
    ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(categoria_id) as total FROM categoria 
      WHERE (categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%')";
} else {
  // Consulta para obtener todos los registros de la tabla y el total de registros de la tabla
  $sql = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";
  $sql_total = "SELECT COUNT(categoria_id) as total FROM categoria";
}

// Obtiene todos los registros de la base de datos y el total de registros de la consulta
$conexion = conexion();

$datos = $conexion->query($sql);
$datos = $datos->fetchAll(PDO::FETCH_ASSOC);

$total = $conexion->query($sql_total);
$total = (int) $total->fetchColumn();

$total_paginas = ceil($total / $registros);

// Mostrar los registros de la tabla en formato HTML
$tabla  .= '
<div class="table-container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th class="has-text-centered">#</th>
          <th class="has-text-centered">Nombre</th>
          <th class="has-text-centered">Ubicación</th>
          <th class="has-text-centered">Productos</th>
          <th class="has-text-centered" colspan="2">Opciones</th>
        </tr>
        
      </thead>
      <tbody>
';
// si hay registros en la base de datos  y la pagina es menor o igual al total de paginas
if ($total >= 1 && $pagina <= $total_paginas) {

  $contador = $inicio + 1;
  $reg_inicio = $inicio + 1;

  foreach ($datos as $rows) {

    $tabla .= ' 
      <tr>
        <td class="has-text-centered">' . $contador . '</td>
        <td class="has-text-left">' . $rows['categoria_nombre'] . '</td>
        <td class="has-text-left">' . substr($rows['categoria_ubicacion'], 0, 25) . '</td>
        <td class="has-text-centered"> 
          <a href="index.php?vista=product_category&category_id=' . $rows['categoria_id'] . '" class="button is-link is-rounded is-small">Ver productos</a>
        </td>
        <td class="has-text-centered">
          <a href="index.php?vista=category_update&category_id_up=' . $rows['categoria_id'] . '" class="button is-success is-rounded is-small">Actualizar</a>
        </td>
        <td class="has-text-centered">
          <a href="' . $url . $pagina . '&category_id_del=' . $rows['categoria_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
        </td>
      </tr>
    ';
    $contador++;
  }

  $reg_final = $contador - 1;
} else {
  // si hay registros en la base de datos
  if ($total >= 1) {
    $tabla .= '
        <tr class="has-text-centered">
          <td colspan="6">
            <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
              Haga clic acá para recargar el listado
            </a>
          </td>
        </tr>
        ';
  } else {
    // si no hay registros en la base de datos
    $tabla .= '
        <tr class="has-text-centered">
          <td colspan="6">
            No hay registros en el sistema
          </td>
        </tr>
        ';
  }
}
$tabla .= '</tbody></table></div>';

if ($total >= 1 && $pagina <= $total_paginas) {
  $tabla .= '
  <p class="has-text-right">Mostrando categorías <strong>' . $reg_inicio . '</strong> 
  al <strong>' . $reg_final . '</strong> de un <strong>total de ' . $total . '</strong></p>
  ';
}

$conexion = null;
echo $tabla;

if ($total > 3 && $pagina <= $total_paginas) {
  // echo paginador_tablas($pagina, $total_paginas, $url, $num_botones);
  echo paginador_tablas($pagina, $total_paginas, $url, 3);
}
