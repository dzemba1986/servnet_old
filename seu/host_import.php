<?php
require("security.php");
//require("include/formDuplicat.php");
require("include/definitions.php");
$daddy = new Daddy();
$query = "SELECT * FROM Podsiec";
$subnets = $daddy->query_assoc_array($query);
$query = "SELECT * FROM Host_import";
$hosts = $daddy->query_assoc_array($query);
foreach ($subnets as $subnet)
{
  if($subnet['vlan']!="2")
    continue;
  $ip = new IpAddress($subnet['address'], $subnet['netmask']);
  foreach($hosts as $host)
  {
    if($host['ip_hosta'] && $ip->czyIpNalezy($host['ip_hosta']))
    {
      $query = "UPDATE Host_import SET netmask='".$subnet['netmask']."', subnet='".$subnet['id']."' WHERE id='".$host['id']."'";
      $daddy->query($query);
    }
  }
}

$zapytanie = "SELECT * FROM Host_import";
$daddy = new Daddy();
$abonenci = $daddy->query_assoc_array($zapytanie);
foreach ($abonenci as $abonent)
{
	if(!$abonent['ip_hosta'] || !$abonent['pakiet'])
		continue;
	echo"<br>";
	print_r($abonent);
//trzeba przygotować tablicę która bedzie zawierała przypisanie długości maski do konkretnej podsieci, bo to będzie zmienne i skrypt nie zadziała
	$hostIP_obj = new IpAddress($abonent['ip_hosta'], $abonent['netmask']);
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
	$tmp = preg_split('/-/', $abonent['net_date']);
	$net_date = $tmp[2].".".$tmp[1].".".substr($tmp[0],-2);
	$tmp = preg_split('/-/', $abonent['resignation_date']);
	$resignation_date = $tmp[2].".".$tmp[1].".".substr($tmp[0],-2);
	
	$parent_device;
	$zapytanie = "SELECT * FROM Adres_ip WHERE ip='".$abonent['switch_ip']."' AND podsiec='37'";
	echo"<br>$zapytanie<br>";

	$parent_device = $daddy->query($zapytanie);
	$parent_device = $parent_device['device'];
	if(!$parent_device)
		die("nie udało się odnaleźć urządzenia nadrzędnego");
	$pakiet;
	switch ($abonent['pakiet'])
	{
		case "6/3":
		$pakiet = 6;
		break;
		case "4/2":
		$pakiet = 4;
                break;
		case "10/5":
		$pakiet = 10;
                break;
		case "30/15":
		$pakiet = 30;
                break;
		case "50/25":
		$pakiet = 50;
		break;
	}
        $ulic;
        switch($abonent['osiedle'])
        {
          case "OWW":
            $ulic = "23990";
            break;
          case "OZ":
            $ulic = "26323";
            break;
          case "OPL":
            $ulic = "16636";
            break;
          case "OP":
            $ulic = "17923";
            break;
          case "WILCZAK":
            $ulic = "24263";
            break;
          case "OK":
            $ulic = "09439";
            break;
          case "NARAMOWICKA":
            $ulic = "13989";
            break;
          case "RYLEJEWA":
            $ulic = "19232";
          case "OPLW":
            $ulic = "24263";
            break;
        }
	$device = array(
		'mac' => $abonent['mac'],
		'exists' => 1,
		'gateway' => $gateway,
		'opis' => $abonent['opis'],
		'device_type' => 'Host',
		'parent_port' => $abonent['port'],
		'parent_device' => $parent_device,
		'osiedle' => $ulic,
		'blok' => $abonent['blok'],
		'klatka' => '',
		'ip' => $ip,
		'uplink_parent_ports' => array('0' => $abonent['port']),
		'uplink_local_ports' => array('0' => '1'));
		
		echo"<br>";
		print_r($device);
		$host = new Host();
		$sql = $host->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$host->dodaj($device, 
			 $abonent['mieszkanie'],
			 $pakiet,
			 '', 
			 $_POST['id_abonenta'], 
			 $_POST['opis_historii'],
			 '', ''); 
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
