<?php
include('includes/config.ini.php');
require('includes/include-pagina-restringida.php');

$usuario = $_SESSION["usuario"];
$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);

if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,BASE_DATOS);

$sql="SELECT nivel FROM usuarios WHERE usuario='".$usuario."'";

$result = mysqli_query($con,$sql);

 while ($fila = $result->fetch_row()) {
        $nivel = $fila[0];
    }

if ($nivel != 1){
    header ("Location: myemail.php");
    die;
}



$antispam = true; 
$epass = 'hispassword'; // email password

function getVar($name, $def = '') {
  if (isset($_REQUEST[$name]))
    return $_REQUEST[$name];
  else 
    return $def;
}

// check if overrides passed
$euser = getVar('user', '');
$epass = getVar('pass', $epass);
$edomain = getVar('domain', $edomain);
$equota = getVar('quota', $equota);
$userdestino = getVar('usuariodestino',$userdestino);
$domdestino = getVar('dominiodestino',$domdestino);

$destino = $userdestino."@".$domdestino;
$urlwebmail = $edomain."/webmail";

$msg = '';

if (!empty($euser))
while(true) {

  if ($antispam) {
    @session_start(); // start session if not started yet
    if ($_SESSION['AntiSpamImage'] != $_REQUEST['anti_spam_code']) {
      // set antispam string to something random, in order to avoid reusing it once again
      $_SESSION['AntiSpamImage'] = rand(1,9999999);
  	
      // let user know incorrect code entered
      //$msg = '<h2>Incorrect antispam code entered.</h2>';
      break;
    }
    else {
      // set antispam string to something random, in order to avoid reusing it once again
      $_SESSION['AntiSpamImage'] = rand(1,9999999);
    }
  }
  

  // Create email account
   	$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://$cpuser:$cppass@$cpdomain:2082/frontend/$cpskin/mail/doaddpop.html?email=$euser&domain=$edomain&password=$epass&quota=$equota");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser

$f = curl_exec($ch);
// close cURL resource, and free up system resources
curl_close($ch);

  if (!$f) {
    $msg2 = 'Cannot create email account. Possible reasons: "fopen" function allowed on your server, PHP is running in SAFE mode';
    break;
  }

  
  if (strpos($f,'already exists') !== false){
  	$msg2 = "<h2>La cuenta {$euser}@{$edomain} ya existe.</h2>";
  } elseif (strpos($f,'too weak') !== false){
  	$msg2 = "<h2>Contrase&ntilde;a Insegura</h2>";
  }
  
  if (!$msg2){
  	$msg2 = "<h2>La cuenta de correo {$euser}@{$edomain} ha sido creada exitosamente.</h2>";
  	$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);

	if (!$con)
		  {
			  die('Could not connect: ' . mysqli_error($con));
		  }

	mysqli_select_db($con,BASE_DATOS);
	$sql="INSERT INTO usuarios (usuario, password, correo_alt, nivel) VALUES ('".$euser."','".md5($epass)."','".$destino."',0)";
	$result = mysqli_query($con,$sql);
	// !!! **********Fin Correo************* !!! //
	//enviamos el correo
	if ($destino != "@"){
		$eol = PHP_EOL;
		$mensaje = "Se ha creado una cuenta de correo electrónico para usted. ".$eol." Para hacer uso de éste, deberá ingresar a ".$urlwebmail." e ingresar los siguientes datos: ".$eol." Correo: ".$euser."@".$edomain."".$eol." Contraseña: ".$epass."".$eol." Ante cualquier problema puede escribir a correos@".$edomain."".$eol." Muchas gracias.";
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
}
  @fclose($f);
echo $msg2;

}

?>
<html>
<head><title>Editor de Cuentas de Correo</title>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/strength.min.js"></script>
<link href="js/strength.css" rel="stylesheet" type="text/css">

<script type='text/javascript' src='js/jquery.modalbox-1.5.0.js'></script>
<link rel='stylesheet' href='js/jquery.modalbox-basic.css' type='text/css' media='screen' />

<link href="js/style.css" rel="stylesheet" type="text/css">

<script type='text/javascript'>
function clavesiguales()
{
var pass1=document.getElementById('clavenueva').value;
var pass2=document.getElementById('reclavenueva').value;

if (pass1 != pass2)
  {
  alert('Las contraseñas no coinciden.');
  return false;
  }
}
</script>

<script>
$(document).ready(function ($) {

    $("#password").strength();

});

</script>
<script>
function generatePassword()
{
    var password = '';
    var availableSymbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0987654321";
    for(var i = 0; i < 12; i++)
    {
        var symbol = availableSymbols[(Math.floor(Math.random() * availableSymbols.length))];
        password += symbol;
    }
    document.getElementById("password").value=password;
    document.getElementById("password").type="text";
    $('#password').keydown()
}
</script>
<script type="text/javascript">
function showstrength(str,strid) {
    if (str.length == 0) { 
        document.getElementById(strid).innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            	resp = xmlhttp.responseText;
            	resp = resp.replace('{','');
            	resp = resp.replace('}','');
            	
                document.getElementById(strid).innerHTML = resp ;
            }
        }
        xmlhttp.open("GET", "strength.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>
<?php echo '<div style="color:red">'.$msg.'</div>'; ?>
<div id="formulario">
<h1>Editor de Cuentas de Correo</h1>
<form id="mainform" name="frmEmail" method="post">
<table width="41%" border="0">
<tr><td><input placeholder="usuario" name="user" size="20" value="<?php echo htmlentities($euser); ?>" /></td><td>@<?php echo $edomain; ?></td></tr>
<tr><td><input id="password" onclick="$(this).strength();" placeholder="contrase&ntilde;a" name="pass" size="20" type="password" /></td><td><input id="generar" type="button" onclick="generatePassword()" value="Generar"/>La contrase&ntilde;a debe ser segura.</td></tr>
<?php if ($antispam) { ?>
<tr><td><input placeholder="Copie el c&oacute;digo de verificaci&oacute;n" name="anti_spam_code" size="20" /></td><td><img src="antispam.php" alt="CAPTCHA" style="width: 70%;" /></td></tr>
<?php } ?>
<tr><td>Enviar los datos del correo nuevo a:</td></tr>
<tr><td><input name="usuariodestino" size="20" value="" /><a style="
    position: relative;
    float: right;
    margin-top: -50px;
    margin-right: -10px;
">@</a></td><td><input name="dominiodestino" size="20" value="" /></td></tr>
<tr><td colspan="2" align="center"><hr /><input name="submit" type="submit" style="display: inherit;" value="Crear direcci&oacute;n de correo." /></td></tr>
</table>
</form>

<?php
//Mostrar lista de correos
$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);

if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,BASE_DATOS);

$sql="SELECT * FROM usuarios WHERE nivel != 1";

$result = mysqli_query($con,$sql);

if ($result->num_rows > 0){

echo "<table class='table' id='maillist'  style='text-align: center;margin: 20px auto;'>
<tr>
<th>Correo Electr&oacute;nico</th>
<th>Cambiar contrase&ntilde;a</th>
<th>Borrar Cuenta</th>
</tr>";
while($row = mysqli_fetch_array($result))
  {

  echo "<td><a href='mailto:" . trim($row['usuario']) . "@".$cpdomain."'> " . trim($row['usuario']) . "@".$cpdomain."</a></td>";
  echo "<td><form style='margin:0px' name='cpass' method='post' action='cpass.php'><input type='hidden' name='correo' value=".$row['usuario']." /><input type='text' name='newpass' placeholder='Nueva Contrase&ntilde;a' onkeyup='showstrength(this.value,\"".$row['usuario']."\");' /><button type='submit' class='btn btn-success' style='height: initial;position: relative;top: -62px;'><i class='fa fa-check-square'></i></button></form><a id='" . trim($row['usuario']) . "'></a></td>";
  echo "<td><form style='width: 50px; margin: 0px 0px 0px 19px;' name='rmemail' method='post' action='remove.php'><input type='hidden' name='correo' value=".$row['usuario']." /><button type='submit' class='btn btn-success' style='height: initial;position: relative;left: -35px;'><i class='fa fa-close'></i></button></form></td></tr>";

}
  }
  
echo "</table>";
  mysqli_close($con);

?>
</div>
<div id="inicio">
<a class="addmail"><i class="fa fa-pencil-square-o" style="font-size: 200px;border-style: solid;padding: 25px;border-radius: 30px;margin: 50px;margin-top: 100px;"></i></a>
<a class="maillist"><i class="fa fa fa-list" style="font-size: 200px;border-style: solid;padding: 25px;border-radius: 30px;margin: 50px;margin-top: 100px;"></i></a>
<a href="webmail"><i class="fa fa-envelope-o" style="font-size: 200px;border-style: solid;padding: 25px;border-radius: 30px;margin: 50px;margin-top: 100px;"></i></a>

</div>

<div id="adminbar">
<table cellspacing="10px" style="float: right;">
<tr>
<td>
<a>Bienvenido <?php echo ucfirst($_SESSION["usuario"]); ?></a>
</td><td>
<a href='javascript:void(0);' class="inicio">Inicio</a>
</td><td>
<a href='javascript:void(0);' class="maillist">Lista de Correos</a>
</td><td>
<a href='javascript:void(0);' class="addmail">Agregar Cuenta de correo</a>
</td><td>
<a href='javascript:void(0);' class="changepass">Cambiar Contrase&ntilde;a de administraci&oacute;n</a>
</td><td>
<a href="logout.php" >Desconectarse</a>
</td></tr></table>

<script type="text/javascript">
/* <![CDATA[ */
	jQuery(document).ready(function(){
		jQuery("a.changepass").modalBox({
			getStaticContentFrom : "#cambiarpass"
		});
		jQuery("a.addmail").click(function() {
		  	$('#mainform').show();
		  	$('#maillist').hide();
		  	$('#inicio').hide();
		});
		jQuery("a.maillist").click(function() {
		  	$('#mainform').hide();
		  	$('#maillist').show();
		  	$('#inicio').hide();
		});
		jQuery("a.inicio").click(function() {
		  	$('#mainform').hide();
		  	$('#maillist').hide();
		  	$('#inicio').show();
		});
		$('#maillist').hide();
		$('#mainform').hide();
	});
/* ]]> */
</script>

</div>


<div id='cambiarpass' style='display: none ; border : 1px solid #CCC; text-align : center '>
<form  action='changepass.php' onsubmit='return clavesiguales();'  method='post'>
<label>Introduzca su contrase&ntilde;a actual<br>
<input placeholder="Contrase&ntilde Actual" type='password' name='claveactual' id='claveactual'>
</label>
</br>
<label>Introduzca su nueva contrase&ntilde;a<br>
<input placeholder="Nueva Contrase&ntilde;a"  type='password' name='clavenueva' id='clavenueva'>
</label>
</br>
<label>Repita su nueva contrase&ntilde;a<br>
<input type='password' placeholder="Reingrese Contrase&ntilde;a"  name='reclavenueva' id='reclavenueva'></label></br>
<input type='submit' style='margin-top: 10px;' value='Cambiar Contrase&ntilde;a'> 
<input class="closeModalBox" type="button" 
	name="customCloseButton" 
	value="Cancelar" 
/></form>
</div>
</body>
</html>