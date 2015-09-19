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

$sql="SELECT * FROM usuarios WHERE usuario = '$email';";
$result = mysqli_query($con,$sql);
if ($result->num_rows > 0){
while($row = mysqli_fetch_array($result))
  {
  $destino = $row['correo_alt']
  $epass = $pass;
  }
  }
// !!! **********Fin Correo************* !!! //
	//enviamos el correo
	if ($destino != "@"){
		$eol = PHP_EOL;
		$mensaje = "Se ha modificado su contraseña. ".$eol." Le recordamos que para revisar su bandeja de entrada deberá ingresar a ".$urlwebmail." e ingresar los siguientes datos: ".$eol." Correo: ".$euser."@".$edomain."".$eol." Contraseña: ".$epass."".$eol." Ante cualquier problema puede escribir a correos@".$edomain."".$eol." Muchas gracias.";
		$body = wordwrap($mensaje, 70, "\r\n");
		$headers = 'From: correos@'.$edomain.$eol;
    		$asunto = 'Se ha creado una cuenta de correo electrónico para usted';
    		
    		// empezamos a armar el mensaje \\
$emsg = "Content-Type: multipart/alternative".$eol;

// En caso de que no podamos leer html \\
$emsg .= "--".$random_hash.$eol;
$emsg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
$emsg .= "Content-Transfer-Encoding: 7bit".$eol;
$emsg .= "Este e-mail requiere que active HTML. ".$eol;
$emsg .= "Si usted está leyendo esto, por favor actualice su cliente de correo. ".$eol;
$emsg .= "------- Mensaje truncado -------".$eol.$eol;

// Lo "normal", que podamos leer html \\
$emsg .= "--".$random_hash.$eol;
$emsg .= "Content-Type: text/html; charset=iso-8859-1".$eol;
$emsg .= "Content-Transfer-Encoding: 7bit".$eol;


$emsg = $body.$eol.$eol; // !!! ***********************

  
  
		mail($destino, $asunto, wordwrap($emsg,70,$eol),$headers);
		
		// !!! **********Fin Correo************* !!! //
}
header('Location: cpemail.php');
die();
?>