<div class="container is-fluid mb-6">
  <h1 class="title">Categorías</h1>
  <h2 class="subtitle">Lista de categoría</h2>
</div>

<div class="container pb-6 pt-6">

  <?php
  require_once "./php/main.php";

  // Eliminar categoria
  if (isset($_GET['category_id_del'])) {
    require_once "./php/categoria_eliminar.php";
  }

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

  // url para los enlaces del paginador
  // establece el número de registros por página
  $url = "index.php?vista=category_list&page=";
  $registros = 3; // Registros por página
  $busqueda = "";

  require_once "./php/categoria_lista.php";
  ?>

</div>