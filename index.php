<?php
include('includes/config.ini.php');
require('includes/include-pagina-restringida.php');
  if (!defined('BASE_DATOS')) {
   header('Location: setup/');
   exit;
  }
header('Location: cpemail.php');
        die();
        
?>