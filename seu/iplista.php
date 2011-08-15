<?php
require("include/definitions.php");
$adres = new IpAddress("10.0.0.242", 27);
foreach($adres->generujPodsiec() as $ip)
{
	echo $ip."<br>";
}

?>
