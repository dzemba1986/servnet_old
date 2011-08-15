<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$daddy->connect();
$subnet_id = mysql_real_escape_string($_GET['subnet']);
$dev_ip = mysql_real_escape_string($_GET['dev_ip']);
$subnet_array = $daddy->getSubnet($subnet_id);
$ip = new IpAddress($subnet_array['address'], $subnet_array['netmask']);
$ip_lista = $ip->generujPodsiec();
$ipiki = $daddy->getFreeFromSubnet($ip_lista, $subnet_id, $dev_ip);
$xml = $daddy->toXml($ipiki, true);
header("Content-type:text/xml; charset=utf-8");
echo $xml;

