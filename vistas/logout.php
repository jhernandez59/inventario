<?php
session_destroy();

if (headers_sent()) {
  echo "<script>document.location.href = 'index.php?vista=login';</script>";
} else {
  header('Location: index.php?vista=login'); // Re-direccionar a la vista home
}
