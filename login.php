<?php
/*
 * Valida un usuario y contraseña o presenta el formulario para hacer login
 */
 include('includes/config.ini.php');
   if (!defined('BASE_DATOS')) {
   header('Location: setup/');
   exit;
  }
session_start();

if ($_SERVER['REQUEST_METHOD']=='POST') { // ¿Nos mandan datos por el formulario?
    //include('includes/config.ini.php'); //incluimos configuración
    include('includes/login.class.php'); //incluimos las funciones
$_SESSION['usuario']   = $_POST['usuario'];
    $Login=new Login();
    //si hace falta cambiamos las propiedades tabla, campo_usuario, campo_contraseña, metodo_encriptacion

    //verificamos el usuario y contraseña mandados
    if ($Login->login($_POST['usuario'],$_POST['password'])) {


        //saltamos al inicio del área restringida
        header('Location: cpemail.php');
        die();
    } else {
        //acciones a realizar en un intento fallido
        //Ej: mostrar captcha para evitar ataques fuerza bruta, bloquear durante un rato esta ip, ....


        //preparamos un mensaje de error y continuamos para mostrar el formulario
        $mensaje='Usuario o contraseña incorrecto.';
    }
} //fin if post
?>
<html>
<head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="js/style.css" rel="stylesheet" type="text/css">
<script>
$(".user").focusin(function(){
  $(".inputUserIcon").css("color", "#e74c3c");
}).focusout(function(){
  $(".inputUserIcon").css("color", "white");
});

$(".pass").focusin(function(){
  $(".inputPassIcon").css("color", "#e74c3c");
}).focusout(function(){
  $(".inputPassIcon").css("color", "white");
});
</script>
</head>
<body>
               
       
       <form action="login.php" method="post" enctype="multipart/form-data">
  <h2><span class="entypo-login"></span> Ingresar al sistema de administraci&oacute;n de cuentas de correo</h2>
  <button class="submit"><span class="entypo-lock"></span></button>
  <span class="entypo-user inputUserIcon"></span>
  <input type="text" name="usuario" class="user" placeholder="usuario"/>
  <span class="entypo-key inputPassIcon"></span>
  <input type="password" name="password" class="pass"placeholder="contrase&ntilde;a"/>
</form>


    </body>
</html>