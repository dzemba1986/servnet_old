<?php
require("security.php");
//require("include/formDuplicat.php");
require("include/definitions.php");
$daddy = new Daddy();
$zapytanie = "SELECT * FROM Podsiec";
$wynik = $daddy->query_assoc_array($zapytanie);
foreach ($wynik as $wiersz)
{
	$ip_A = new IpAddress($wiersz['address'], $wiersz['netmask']);
	$ip = $ip_A->getNetworkAddress();
	$ip = $ip_A->decToHR($ip);
	$zapytanie = "UPDATE Podsiec SET address='$ip' WHERE id='".$wiersz['id']."'";
	echo"<br>".$wiersz['address']."  $zapytanie";
	$daddy->query($zapytanie);
}
