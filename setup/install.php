<?php

function step_1(){
  if (isset($_POST['submit']) && $_POST['submit']=="Install!") {
   $database_host=isset($_POST['database_host'])?$_POST['database_host']:"";
   $database_name=isset($_POST['database_name'])?$_POST['database_name']:"";
   $database_username=isset($_POST['database_username'])?$_POST['database_username']:"";
   $database_password=isset($_POST['database_password'])?$_POST['database_password']:"";
   $admin_name=isset($_POST['admin_name'])?$_POST['admin_name']:"";
   $admin_password=isset($_POST['admin_password'])?$_POST['admin_password']:"";
   $cpuser=isset($_POST['cpuser'])?$_POST['cpuser']:"";
   $cppass=isset($_POST['cppass'])?$_POST['cppass']:"";
   $cpdomain=isset($_POST['cpdomain'])?$_POST['cpdomain']:"";
   $cpskin=isset($_POST['cpskin'])?$_POST['cpskin']:"";
   $edomain=isset($_POST['edomain'])?$_POST['edomain']:"";
   $equota=isset($_POST['equota'])?$_POST['equota']:"";
   
  if (empty($admin_name) || empty($admin_password) || empty($database_host) || empty($database_username) || empty($database_name)) {
   echo "All fields are required! Please re-enter.<br />";
  } else {
   $connection = mysql_connect($database_host, $database_username, $database_password);
   mysql_select_db($database_name, $connection);
  
   $file ='data.sql';
   if ($sql = file($file)) {
   $query = '';
   foreach($sql as $line) {
    $tsl = trim($line);
   if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
   $query .= $line;
  
   if (preg_match('/;\s*$/', $line)) {
  
    mysql_query($query, $connection);
    $err = mysql_error();
    if (!empty($err))
      break;
   $query = '';
   }
   }
   }
   @mysql_query("INSERT INTO usuarios SET usuario='".$admin_name."', password = '" . md5($admin_password) . "',nivel='1'");
   mysql_close($connection);
   }
   $database_inf="<?php
     define('SERVIDOR_MYSQL', '".$database_host."');
     define('BASE_DATOS', '".$database_name."');
     define('USUARIO_MYSQL', '".$database_username."');
     define('PASSWORD_MYSQL', '".$database_password."');
     define('ADMIN_NAME', '".$admin_name."');
     define('ADMIN_PASSWORD', '".$admin_password."');
     
     
     \$cpuser = '".$cpuser."'; // cPanel username
     \$cppass = '".$cppass ."'; // cPanel password
     \$cpdomain = '".$cpdomain ."'; // cPanel domain or IP
     \$cpskin = '".$cpskin ."';

     \$edomain = '".$edomain ."'; // email domain (usually same as cPanel domain above)
     \$equota = ".$equota."; // amount of space in megabytes
     ?>";
  file_put_contents("../includes/config.ini.php",$database_inf);
  header("Location: index.php");
  }
  }
  };
  step_1();
?>

<!DOCTYPE html>
<html>
<head>
<title>Installation Script</title>
</head>

<body>
  <form method="post" action="install.php?step=1">
  <p>
   <input type="text" name="database_host" value='localhost' size="30">
   <label for="database_host">Database Host</label>
 </p>
 <p>
   <input type="text" name="database_name" size="30" value="<?php echo $database_name; ?>">
   <label for="database_name">Database Name</label>
 </p>
 <p>
   <input type="text" name="database_username" size="30" value="<?php echo $database_username; ?>">
   <label for="database_username">Database Username</label>
 </p>
 <p>
   <input type="text" name="database_password" size="30" value="<?php echo $database_password; ?>">
   <label for="database_password">Database Password</label>
  </p>
  <br/>
  <p>
   <input type="text" name="admin_name" size="30" value="<?php echo $username; ?>">
   <label for="username">Admin Login</label>
 </p>
 <p>
   <input name="admin_password" type="text" size="30" maxlength="15" value="<?php echo $password; ?>">
   <label for="password">Admin Password</label>
  </p>
 <p>
   <input name="cpuser" type="text" size="30"  >
   <label for="cpuser">cPanel Username</label>
  </p>
  <p>
   <input name="cppass" type="text" size="30" >
   <label for="cppass ">cPanel Password</label>
  </p>
  <p>
   <input name="cpdomain" type="text" size="30" >
   <label for="cpdomain">cPanel Domain</label>
  </p>
  <p>
   <input name="cpskin" type="text" size="30" value="x3">
   <label for="cpskin">cPanel skin</label>
  </p>
  <p>
   <input name="edomain" type="text" size="30">
   <label for="edomain ">Email Domain</label>
  </p>
  <p>
   <input name="equota" type="text" size="30" value="250">
   <label for="equota">Amount of space in megabytes</label>
  </p>
  <p>
   <input type="submit" name="submit" value="Install!">
  </p>
  </form>
  </body>
  </html>