<?php

require('includes/include-pagina-restringida.php'); //el incude para vericar que estoy logeado. Si falla salta a la página de login.php

include('includes/config.ini.php');



$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);


if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

$usuario = $_SESSION["usuario"];
$oldpass = md5($_POST["claveactual"]);
$newpass = $_POST["clavenueva"];

$password = md5($newpass);
mysqli_select_db($con,BASE_DATOS);

$sql = "SELECT password FROM usuarios WHERE usuario = '$usuario';";

$resultado = mysqli_query($con,$sql);

$row=$resultado->fetch_assoc();
   $oldpassworddb=$row['password'];




if ($oldpassworddb != $oldpass ){

header ("Location: cpemail.php");

} else {

$sql="UPDATE usuarios SET password = '$password' WHERE usuario = '$usuario';";

$result = mysqli_query($con,$sql);

if ($result->num_rows >0){

        echo "La contrase&ntilde;a no fue modificada";
    } else {
       Login::logout(); //vacia la session del usuario actual
	header ("Location: login.php?mess=1");
    }

}






?>