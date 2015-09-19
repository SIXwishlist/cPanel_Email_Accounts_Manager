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

if ($nivel != 0){
    header ("Location: cpemail.php");
    die;
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
</head>
<body>
<?php
//Mostrar lista de correos
$con=mysqli_connect(SERVIDOR_MYSQL,USUARIO_MYSQL,PASSWORD_MYSQL,BASE_DATOS);

if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,BASE_DATOS);

$sql="SELECT * FROM usuarios WHERE usuario = '".$_SESSION["usuario"]."'";

$result = mysqli_query($con,$sql);

if ($result->num_rows > 0){

while($row = mysqli_fetch_array($result))
  {
  $correo_alt = $row['correo_alt']; 
  $datos = explode("@", $correo_alt);
  $cuenta_alt = $datos[0];
  $dominio_alt = $datos[1];
  }}
 ?>

<div id="formulario">
<h1>Editor de Cuentas de Correo</h1>
<form id="mainform" name="frmEmail" method="post" action='cpass.php'>
<table width="41%" border="0">
<tr><td><?php echo $_SESSION["usuario"]; ?></td><td>@<?php echo $edomain; ?></td></tr>
<tr><td><input type="hidden" name="correo" value="<?php echo $_SESSION["usuario"]; ?>"><input id="password" placeholder="Nueva Contrase&ntilde;a" name="newpass" size="20" type="password" /></td><td><input id="generar" type="button" onclick="generatePassword()" value="Generar"/>La contrase&ntilde;a debe ser segura.</td></tr>

<tr><td>Correo Alternativo:</td></tr>
<tr><td><input name="usuariodestino" size="20" value="<?php echo $cuenta_alt; ?>" /><a style="
    position: relative;
    float: right;
    margin-top: -50px;
    margin-right: -10px;
">@</a></td><td><input name="dominiodestino" size="20" value="<?php echo $dominio_alt; ?>" /></td></tr>
<tr><td colspan="2" align="center"><hr /><input name="submit" type="submit" style="display: inherit;" value="Modificar Datos." /></td></tr>
</table>
</form>
</div>
<div id="inicio">
<a class="changepass"><i class="fa fa-pencil-square-o" style="font-size: 200px;border-style: solid;padding: 25px;border-radius: 30px;margin: 50px;margin-top: 100px;"></i></a>
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
<a href="webmail" class="webmail">Ir al Webmail</a>
</td><td>
<a href='javascript:void(0);' class="changepass">Modificar Datos</a>
</td><td>
<a href="logout.php" >Desconectarse</a>
</td></tr></table>

<script type="text/javascript">
/* <![CDATA[ */
	jQuery(document).ready(function(){
		jQuery("a.changepass").click(function() {
		  	$('#mainform').show();
		  	$('#inicio').hide();
		});
		jQuery("a.inicio").click(function() {
		  	$('#mainform').hide();
		  	$('#inicio').show();
		});
		$('#mainform').hide();
	});
/* ]]> */
</script>

</div>



</body>
</html>