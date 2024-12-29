<?php
$modulo_buscador = limpiar_cadena($_POST['modulo_buscador']);
$modulos = ["usuario", "categoria", "producto"];

if (in_array($modulo_buscador, $modulos)) {

  $modulos_url = [
    'usuario' => 'user_search',
    'categoria' => 'category_search',
    'producto' => 'product_search'
  ];

  // url para la vista que se debe cargar
  $modulos_url = $modulos_url[$modulo_buscador];

  // para la variable de session
  $modulo_buscador = "busqueda_" . $modulo_buscador;

  // iniciar busqueda, consiste en definir la variable de session
  if (isset($_POST['txt_buscador'])) {
    $txt = limpiar_cadena($_POST['txt_buscador']);
    if ($txt == "") {
      echo '
      <div class="notification is-danger is-light">
        <strong>¡Ocurrido un error inesperado!</strong><br>
        Introduce un término de búsqueda
      </div>
    ';
    } else {
      if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}", $txt)) {
        echo '
        <div class="notification is-danger is-light">
          <strong>¡Ocurrido un error inesperado!</strong><br>
          El término de búsqueda no coincide con el formato solicitado
        </div>
      ';
      } else {
        $_SESSION[$modulo_buscador] = $txt;
        header("Location: index.php?vista=$modulos_url", true, 303);
        exit();

        /* echo '
        <script>
          window.location = "index.php?vista=user_search";
        </script>
        '; */
      }
    }
  }

  // eliminar busqueda, consiste en eliminar la variable de session
  if (isset($_POST['eliminar_buscador'])) {
    unset($_SESSION[$modulo_buscador]);
    header("Location: index.php?vista=$modulos_url", true, 303);
    exit();
  }
} else {
  echo '
  <div class="notification is-danger is-light">
    <strong>¡Ocurrido un error inesperado!</strong><br>
    No podemos procesar la solicitud
  </div>
';
}
