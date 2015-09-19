<?php
include('includes/config.ini.php');
require('includes/include-pagina-restringida.php');


$q = $_REQUEST["q"];

  // check password
   	$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://$cpuser:$cppass@$cpdomain:2082/backend/passwordstrength.cgi?password=$q");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser

$f = curl_exec($ch);
// close cURL resource, and free up system resources
curl_close($ch);
$f = str_replace("{", "", $f);
$f = str_replace("}", "", $f);
//$f = str_replace("strength","",$f);
$f = str_replace("\"","",$f);
//$f = str_replace(":","",$f);
echo $f;
?>