<?php require './inc/session_start.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include './inc/head.php'; ?>
</head>

<body>

  <?php

  if (!isset($_GET['vista']) || $_GET['vista'] == '') {
    $_GET['vista'] = 'login';
  }

  if (file_exists('./vistas/' . $_GET['vista'] . '.php') && $_GET['vista'] != '404' && $_GET['vista'] != 'login') {

    include './inc/navbar.php';
    include './vistas/' . $_GET['vista'] . '.php';
    include './inc/script.php';
    //
  } else {
    if ($_GET['vista'] == 'login') {
      include './vistas/login.php';
    } else {
      include './vistas/404.php';
    }
  }


  /* switch ($_GET['vista']) {
    case 'login':
      include './vistas/login.php';
      break;

    case 'home':
      include './inc/navbar.php';
      include './vistas/' . $_GET['vista'] . '.php';
      include './inc/script.php';
      break;

    case '404':
      include './vistas/404.php';
      break;

    default:
      include './vistas/login.php';
      break;
  }
 */
  ?>

</body>

</html>