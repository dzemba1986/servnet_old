<?php
require("security.php");
//require("include/formDuplicat.php");
require("include/definitions.php");

$zapytanie = "SELECT * FROM Host_import";
$daddy = new Daddy();
$abonenci = $daddy->query_assoc_array($zapytanie);
foreach ($abonenci as $abonent)
{
	if($abonent['opis']!='VoIP')
		continue;
	echo"<br>";
	print_r($abonent);
//trzeba przygotować tablicę która bedzie zawierała przypisanie długości maski do konkretnej podsieci, bo to będzie zmienne i skrypt nie zadziała
	$hostIP_obj = new IpAddress($abonent['ip_hosta'], 24);
	$podsiec_ip = $hostIP_obj->getHrNetworkAddress(); 
	$zapytanie = "SELECT * FROM Podsiec WHERE address='$podsiec_ip'";
	echo"<br>$zapytanie<br>";
	$podsiec_array = $daddy->query($zapytanie);
	if(!$podsiec_array)
		die("Nie odnaleziono podsieci!");
	$ip = array();
	$ip[0]['ip'] = $abonent['ip_hosta'];
	$ip[0]['podsiec'] = $podsiec_array['id'];
	$ip[0]['vlan'] = $podsiec_array['vlan'];
	$ip[0]['main'] = 1;

	if(!$daddy->sprawdz_ip_czywolne($ip[0]['ip'], $ip[0]['podsiec']))
	{
		echo"<br>Host już istnieje<br>";
		continue;
	}

	$gateway = $hostIP_obj->getHrFirst();
	$parent_device;
	$zapytanie = "SELECT Adres_ip.*, Lokalizacja.* FROM Adres_ip 
                        LEFT JOIN Device ON Adres_ip.device=Device.dev_id 
                        LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
                        WHERE ip='".$abonent['switch_ip']."' AND podsiec='37'";
	echo"<br>$zapytanie<br>";

	$parent_device = $daddy->query($zapytanie);
	if(!$parent_device)
		die("nie udało się odnaleźć urządzenia nadrzędnego");
	$pakiet;
	$device = array(
		'mac' => $abonent['mac'],
		'exists' => 1,
		'gateway' => $gateway,
		'opis' => $abonent['opis'],
		'device_type' => 'Bramka_voip',
		'parent_port' => $abonent['port'],
		'parent_device' => $parent_device['device'],
		'osiedle' => $parent_device['ulic'],
		'blok' => $parent_device['nr_bloku'],
		'klatka' => $parent_device['klatka'],
		'ip' => $ip,
		'uplink_parent_ports' => array('0' => $abonent['port']),
		'uplink_local_ports' => array('0' => 'Internet'));
		
		echo"<br>";
		print_r($device);
		$host = new Bramka_voip();
		$sql = $host->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$host->dodaj($device, 
			 '',
			 '12',
			 '3',
			 'import automatyczny'); 
		if($host->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano hosta.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano hosta!");
		}
	//a teraz czas na importowanie daty uruchomienia
	
}
