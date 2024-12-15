<?php

$inicio = ($pagina > 0) ? ($pagina * $registros) - $registros : 0;
$tabla  = "";

if (isset($busqueda) && $busqueda != "") {

  $sql = "SELECT * FROM usuario WHERE ((usuario_id != '" . $_SESSION['id'] . "') 
    AND (usuario_nombre LIKE '%$busqueda%' 
    OR usuario_apellido LIKE '%$busqueda%' 
    OR usuario_usuario LIKE '%$busqueda%' 
    OR usuario_email LIKE '%$busqueda%')) 
    ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(usuario_id) as total FROM usuario 
      WHERE ((usuario_id != '" . $_SESSION['id'] . "') 
      AND (usuario_nombre LIKE '%$busqueda%' 
      OR usuario_apellido LIKE '%$busqueda%' 
      OR usuario_usuario LIKE '%$busqueda%' 
      OR usuario_email LIKE '%$busqueda%'))";
} else {

  $sql = "SELECT * FROM usuario WHERE usuario_id != '" . $_SESSION['id'] . "' 
    ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(usuario_id) as total FROM usuario 
      WHERE usuario_id != '" . $_SESSION['id'] . "' ";
}

$conexion = conexion();

$datos = $conexion->query($sql);
$datos = $datos->fetchAll(PDO::FETCH_ASSOC);

$total = $conexion->query($sql_total);
$total = (int) $total->fetch()['total'];

$total_paginas = ceil($total / $registros);

$tabla  .= '
<div class="table-container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
      <thead>
        <tr>
          <th class="has-text-centered">#</th>
          <th class="has-text-centered">Nombres</th>
          <th class="has-text-centered">Apellidos</th>
          <th class="has-text-centered">Usuario</th>
          <th class="has-text-centered">Email</th>
          <th class="has-text-centered" colspan="2">Opciones</th>
        </tr>
      </thead>
      <tbody>
';

if ($total >= 1 && $pagina <= $total_paginas) {

  $contador = $inicio + 1;
  $pag_inicio = $inicio + 1;

  foreach ($datos as $rows) {

    $tabla .= '
      <tr>
        <td class="has-text-centered">' . $contador . '</td>
        <td class="has-text-left">' . $rows['usuario_nombre'] . '</td>
        <td class="has-text-left">' . $rows['usuario_apellido'] . '</td>
        <td class="has-text-left">' . $rows['usuario_usuario'] . '</td>
        <td class="has-text-left">' . $rows['usuario_email'] . '</td>
        <td>
          <a href="index.php?view=user_update&user_id_up=' . $rows['usuario_id'] . '" class="button is-success is-rounded is-small">Actualizar</a>
        </td>
        <td>
          <a href="' . $url . $pagina . '&user_id_del=' . $rows['usuario_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
        </td>
      </tr>
    ';
    $contador++;
  }

  $pag_final = $contador - 1;
} else {
  if ($total >= 1) {
    $tabla .= '
        <tr class="has-text-centered">
          <td colspan="7">
            <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
              Haga clic acá para recargar el listado
            </a>
          </td>
        </tr>
        ';
  } else {
    $tabla .= '
        <tr class="has-text-centered">
          <td colspan="7">
            No hay registros en el sistema
          </td>
        </tr>
        ';
  }
}

$tabla .= '</tbody></table></div>';

if ($total >= 1 && $pagina <= $total_paginas) {
  $tabla .= '
  <p class="has-text-right">Mostrando usuarios <strong>' . $pag_inicio . '</strong> 
  al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>

  ';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $total_paginas) {
  // echo paginador_tablas($pagina, $total_paginas, $url, $num_botones);
  echo paginador_tablas($pagina, $total_paginas, $url, 3);
}