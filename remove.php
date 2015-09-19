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

  // delete email account
   	$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://$cpuser:$cppass@$cpdomain:2082/frontend/$cpskin/mail/realdelpop.html?email=$email&domain=$cpdomain");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser

$f = curl_exec($ch);
// close cURL resource, and free up system resources
curl_close($ch);

 $con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);

if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }
mysqli_select_db($con,BASE_DATOS);

$sql="DELETE FROM usuarios WHERE usuario = '".$email."' ";

$result = mysqli_query($con,$sql);
header('Location: cpemail.php');
die();
?>