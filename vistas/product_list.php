<div class="container is-fluid mb-6">
  <h1 class="title">Productos</h1>
  <h2 class="subtitle">Lista de productos</h2>
</div>

<div class="container pb-6 pt-6">

  <?php
  require_once "./php/main.php";

  /* // Eliminar categoria
  if (isset($_GET['category_id_del'])) {
    require_once "./php/categoria_eliminar.php";
  }
 */
  // Obtiene la pagina actual
  if (!isset($_GET['page'])) {
    $pagina = 1;
  } else {
    $pagina = (int) $_GET['page'];
    if ($pagina <= 1) {
      $pagina = 1;
    }
  }
  $pagina = limpiar_cadena($pagina);

  $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

  // url para los enlaces del paginador
  // establece el número de registros por página
  $url = "index.php?vista=product_list&page=";
  $registros = 3; // Registros por página
  $busqueda = "";

  require_once "./php/producto_lista.php";
  ?>


</div>