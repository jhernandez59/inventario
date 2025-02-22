<?php

// Establece los limites de los registros a mostrar inicio y total
$inicio = ($pagina > 0) ? ($pagina * $registros) - $registros : 0;
$tabla = "";

$campos = "producto.producto_id, producto.producto_codigo, producto.producto_nombre, 
    producto.producto_precio, producto.producto_stock, producto.producto_foto,
    categoria.categoria_nombre, usuario.usuario_nombre, usuario.usuario_apellido";

if (isset($busqueda) && $busqueda != "") {
  // busqueda de productos por codigo o por nombre
  $sql = "SELECT $campos FROM producto INNER JOIN categoria 
    ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario 
    ON producto.usuario_id = usuario.usuario_id WHERE 
    (producto.producto_codigo LIKE '%$busqueda%' 
    OR producto.producto_nombre LIKE '%$busqueda%') 
    ORDER BY producto.producto_nombre 
    ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(producto_id) as total FROM producto 
      WHERE (producto_nombre LIKE '%$busqueda%' OR producto_codigo LIKE '%$busqueda%')";
} elseif ($categoria_id > 0) {
  // busqueda de productos por una sola categoria
  $sql = "SELECT $campos FROM producto INNER JOIN categoria 
    ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario 
    ON producto.usuario_id = usuario.usuario_id WHERE 
    producto.categoria_id = '$categoria_id' 
    ORDER BY producto.producto_nombre 
    ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(producto_id) as total FROM producto 
      WHERE categoria_id = '$categoria_id'";
} else {
  // Consulta para obtener todos los productos por categoria y por usuario 
  $sql = "SELECT $campos FROM producto INNER JOIN categoria 
    ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario 
    ON producto.usuario_id = usuario.usuario_id ORDER BY producto.producto_nombre 
    ASC LIMIT $inicio, $registros";

  $sql_total = "SELECT COUNT(producto_id) as total FROM producto";
}

// Obtiene todos los registros de la base de datos y el total de registros de la consulta
$conexion = conexion();

$datos = $conexion->query($sql);
$datos = $datos->fetchAll(PDO::FETCH_ASSOC);

$total = $conexion->query($sql_total);
$total = (int) $total->fetchColumn();

$total_paginas = ceil($total / $registros);

// si hay registros en la base de datos  y la pagina es menor o igual al total de paginas
if ($total >= 1 && $pagina <= $total_paginas) {

  $contador = $inicio + 1;
  $reg_inicio = $inicio + 1;

  foreach ($datos as $rows) {

    $tabla .= ' 
            <article class="media">
            <figure class="media-left">
              <p class="image is-64x64">';
    if (is_file("./img/producto/" . $rows['producto_foto'])) {
      $tabla .= '<img src="./img/producto/' . $rows['producto_foto'] . '">';
    } else {
      $tabla .= '<img src="./img/producto.png">';
    }
    $tabla .= '</p>
            </figure>
            <div class="media-content">
              <div class="content">
                <p>
                  <strong>' . $contador . ' - ' . $rows['producto_nombre'] . '</strong><br>
                  <strong>CODIGO:</strong> ' . $rows['producto_codigo'] . ',
                  <strong>PRECIO:</strong> $' . $rows['producto_precio'] . ',
                  <strong>STOCK:</strong> ' . $rows['producto_stock'] . ',
                  <strong>CATEGORIA:</strong> ' . $rows['categoria_nombre'] . ',
                  <strong>REGISTRADO POR:</strong> ' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '
                </p>
              </div>
              <div class="has-text-right">
                <a href="index.php?vista=product_img&product_id_up=' . $rows['producto_id'] . '" class="button is-link is-rounded is-small">Imagen</a>

                <a href="index.php?vista=product_update&product_id_up=' . $rows['producto_id'] . '" class="button is-success is-rounded is-small">Actualizar</a>

                <a href="' . $url . $pagina . '&product_id_del=' . $rows['producto_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
              </div>
            </div>
          </article>

          <hr>
    ';
    $contador++;
  }

  $reg_final = $contador - 1;
} else {
  // si hay registros en la base de datos
  if ($total >= 1) {
    $tabla .= '<p class="has-text-centered">
              <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                Haga clic acá para recargar el listado
              </a>        
              </p>
        ';
  } else {
    // si no hay registros en la base de datos
    $tabla .= '<p class="has-text-centered">No hay registros en el sistema</p>';
  }
}

if ($total >= 1 && $pagina <= $total_paginas) {
  $tabla .= '
  <p class="has-text-right">Mostrando productos <strong>' . $reg_inicio . '</strong> 
  al <strong>' . $reg_final . '</strong> de un <strong>total de ' . $total . '</strong></p>
  ';
}

$conexion = null;
echo $tabla;

if ($total > 3 && $pagina <= $total_paginas) {
  // echo paginador_tablas($pagina, $total_paginas, $url, $num_botones);
  echo paginador_tablas($pagina, $total_paginas, $url, 3);
}
