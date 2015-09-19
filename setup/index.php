<?php
  require '../includes/config.ini.php';
  if (!defined('BASE_DATOS')) {
   header('Location: install.php');
   exit;
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Installation Script</title>
<script type="text/javascript">
    window.setTimeout(function(){

        // Move to a new location or you can do something else
        window.location.href = "../webmail/install/";

    }, 4000);
</script>
</head>
<body>

  <p>Instalaci&oacute;n completa.</p>
  <p>Ser&aacute; redirigido a la instalaci&oacute;n del cliente webmail en unos instantes.</p>
</body>
</html>