<?php
/* 
 * Asegura la página en la que se incluya este script.
 */

include_once('includes/login.class.php'); //incluimos las funciones

if (!Login::estoy_logeado()) { // si no estoy logeado
    header('Location: login.php'); //saltamos a la página de login
    die('Acceso no autorizado'); // por si falla el header (solo se pueden mandar las cabeceras si no se ha impreso nada)
}

//si esta logeado el usuario continua con el script.
?>