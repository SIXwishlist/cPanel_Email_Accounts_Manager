<?php
include('includes/config.ini.php');
require('includes/include-pagina-restringida.php');


function getVar($name, $def = '') {
  if (isset($_REQUEST[$name]))
    return $_REQUEST[$name];
  else 
    return $def;
}

// check if overrides passed
$email = getVar('correo', '');
$pass = getVar('newpass','');
$spass = md5($pass);
$usuariodestino = getVar('usuariodestino','');
$dominiodestino =getVar('dominiodestino','');
$alt_mail = $usuariodestino."@".$dominiodestino;


$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);
mysqli_select_db($con,BASE_DATOS);
if ($pass != ""){
$sql="UPDATE usuarios SET password = '$spass' WHERE usuario = '$email';";
$result = mysqli_query($con,$sql);
}
if ($alt_mail != "@" and $alt_mail != ""){
$sql="UPDATE usuarios SET correo_alt = '$alt_mail' WHERE usuario = '$email';";
$result = mysqli_query($con,$sql);
}
  // update password
   	$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://$cpuser:$cppass@$cpdomain:2082/frontend/$cpskin/mail/passwd_pop?email=$email&password=$pass&domain=$cpdomain");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser

$f = curl_exec($ch);
// close cURL resource, and free up system resources
curl_close($ch);



header('Location: cpemail.php');
die();
?>