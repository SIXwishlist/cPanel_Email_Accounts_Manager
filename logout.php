<?php
/* 
 * Cierra la sesión como usuario validado
 */

include('includes/login.class.php'); //incluimos las funciones
Login::logout(); //vacia la session del usuario actual
header('Location: login.php'); //saltamos a login.php

?>