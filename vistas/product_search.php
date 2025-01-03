<div class="container is-fluid mb-6">
  <h1 class="title">Productos</h1>
  <h2 class="subtitle">Buscar producto</h2>
</div>

<div class="container pb-6 pt-6">

  <?php
  require_once './php/main.php';

  if (isset($_POST['modulo_buscador'])) {
    require_once "./php/buscador.php";
  }

  // Mostrar el formulario de busqueda cuando no se ha realizado ninguna busqueda
  if (!isset($_SESSION['busqueda_producto']) && empty($_SESSION['busqueda_producto'])) {
  ?>

    <div class="columns">
      <div class="column">
        <form action="" method="POST" autocomplete="off">
          <input type="hidden" name="modulo_buscador" value="producto">
          <div class="field is-grouped">
            <p class="control is-expanded">
              <input class="input is-rounded" type="text" name="txt_buscador"
                placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30">
            </p>
            <p class="control">
              <button class="button is-info" type="submit">Buscar</button>
            </p>
          </div>
        </form>
      </div>
    </div>

  <?php } else {
    // Cancelar la busqueda 
  ?>
    <div class="columns">
      <div class="column">
        <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off">
          <input type="hidden" name="modulo_buscador" value="producto">
          <input type="hidden" name="eliminar_buscador" value="producto">
          <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_producto']; ?>”</strong></p>
          <br>
          <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
        </form>
      </div>
    </div>

  <?php  // Mostrar el listado de productos con el resultado de la busqueda
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

    // para buscar un producto en especifico por categoria
    $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

    // url para los enlaces del paginador
    // establece el número de registros por página
    $url = "index.php?vista=product_search&page=";
    $registros = 3; // Registros por página
    $busqueda = $_SESSION['busqueda_producto'];

    require_once "./php/producto_lista.php";
  }  ?>

  <!-- <article class="media">
    <figure class="media-left">
      <p class="image is-64x64">
        <img src="./img/producto.png">
      </p>
    </figure>
    <div class="media-content">
      <div class="content">
        <p>
          <strong>1 - Nombre de producto</strong><br>
          <strong>CODIGO:</strong> 00000000,
          <strong>PRECIO:</strong> $10.00,
          <strong>STOCK:</strong> 21,
          <strong>CATEGORIA:</strong> Nombre categoria,
          <strong>REGISTRADO POR:</strong> Nombre de usuario
        </p>
      </div>
      <div class="has-text-right">
        <a href="#" class="button is-link is-rounded is-small">Imagen</a>
        <a href="#" class="button is-success is-rounded is-small">Actualizar</a>
        <a href="#" class="button is-danger is-rounded is-small">Eliminar</a>
      </div>
    </div>
  </article>


  <p class="has-text-centered">
    <a href="#" class="button is-link is-rounded is-small mt-4 mb-4">
      Haga clic acá para recargar el listado
    </a>
  </p>

  <p class="has-text-centered">No hay registros en el sistema</p>


  <hr>


  <p class="has-text-right">Mostrando productos <strong>1</strong> al <strong>17</strong> de un <strong>total de 17</strong></p>


  <nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">
    <a class="pagination-previous" href="#">Anterior</a>

    <ul class="pagination-list">
      <li><a class="pagination-link" href="#">1</a></li>
      <li><span class="pagination-ellipsis">&hellip;</span></li>
      <li><a class="pagination-link is-current" href="#">2</a></li>
      <li><a class="pagination-link" href="#">3</a></li>
      <li><span class="pagination-ellipsis">&hellip;</span></li>
      <li><a class="pagination-link" href="#">3</a></li>
    </ul>

    <a class="pagination-next" href="#">Siguiente</a>
  </nav> -->


</div>