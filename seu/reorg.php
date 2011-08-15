<?php
$filename = ".reorg.lock";
if (file_exists($filename))
 die("Skrypt mozna uruchomic tylko raz!");
require("security.php");
require("include/definitions.php");

$daddy = new Daddy();

//  1  - Podnieść o 2^8 ip wszystkich adresów z podsieci 46.175.46.128/26

$query = "SELECT * FROM Adres_ip WHERE podsiec='110'";
$ips = $daddy->query($query);
foreach($ips as $ip)
{
	$ip_obj = new IpAddress($ip['ip'], 26);
	$ip_obj->shift(pow(2,8));
	$query = "UPDATE Adres_ip SET ip='".$ip_obj->getAddress()."' WHERE ip='".$ip['ip']."' AND podsiec='110'";
	$daddy->query($query);
}

$query = "UPDATE Adres_ip SET ip='46.175.46.129' WHERE ip='46.175.46.193' AND podsiec='111'";
	$daddy->query($query);

//  2  - Zmienić adres podsieci i wartość maski z 46.175.46.128/26 na 46.175.47.128/25

$query = "UPDATE Podsiec SET address='46.175.47.128', netmask='25' WHERE id='110'";
$daddy->query($query);

//  3  - Zmienić adres podsieci i wartość maski z 46.175.46.192/26 na 46.175.46.128/25 

$query = "UPDATE Podsiec SET address='46.175.46.128', netmask='25' WHERE id='111'";
$daddy->query($query);
$file = fopen($filename, "w+");
fwrite($file, "REORGLOCK");
fclose($file);
echo " Reorganizacja zakonczona pomyslnie.";
