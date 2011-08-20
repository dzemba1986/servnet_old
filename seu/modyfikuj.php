<?php
require("security.php");
require("include/formDuplicat.php");
require("include/definitions.php");
$user = $_SESSION['user_id'];
$device_type = $_POST['device_type'];

if(isset($_POST['dodaj']))
{
        if(!$_POST['osiedle'])
          die('Nie podano osiedla!');
	$device = array(
		'dev_id' => $_POST['dev_id'],
		'exists' => $_POST['exists'],
		'mac' => $_POST['mac'],
		'other_name' => $_POST['other_name'],
		'gateway' => $_POST['gateway'],
		'opis' => $_POST['opis'],
		'device_type' => $_POST['device_type'],
		'parent_port' => $_POST['parent_port'],
		'parent_device' => $_POST['parent_device'],
		'osiedle' => $_POST['osiedle'],
		'blok' => $_POST['blok'],
		'klatka' => $_POST['klatka'],
		'uplink_parent_ports' => $_POST['uplink_parent_select'],
		'uplink_local_ports' => $_POST['uplink_local_select']);
        if(defined('DEBUG'))
	  print_r($device);
	$ip = array();
	foreach($_POST as $key=>$dana)
	{
		if(substr($key, 0, 3)=="_ip")
		{
			$ip[substr($key, 3)]['ip'] = $dana;
			if(substr($key, 3)==1)
				$ip[substr($key, 3)]['main'] = 1;
		}
		else if(substr($key, 0, 8)=="_podsiec")
			$ip[substr($key, 8)]['podsiec'] = $dana;
		else if(substr($key, 0, 5)=="_vlan")
			$ip[substr($key, 5)]['vlan'] = $dana;
	}
	$obj = new Device();
//	if(! $obj->sprawdzIp($ip) ||! $obj->sprawdzDevice($device))
//		exit();
	$device['ip'] = $ip;
        if(defined('DEBUG'))
        {
	  echo"<br>pobrane z formularza<br>";
	  print_r($device['ip']);
        }
	switch($_POST['device_type'])
	{
	case 'Switch_bud':
		$switch = new Switch_bud();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->modyfikuj($device, 
			$_POST['typ'],
			$_POST['sn'], 
			$_POST['opis_historii']);
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Uaktualniono przełącznik.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono przełącznika!");
		}
	break;
	case 'Kamera':
		$kamera = new Kamera();
		$sql = $kamera->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$kamera->modyfikuj($device, 
			$_POST['sn'], 
			$_POST['opis_historii']);
		if($kamera->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Uaktualniono kamerę.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono kamery!");
		}
	break;
	case 'Serwer':
		$server = new Serwer();
		$sql = $server->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$server->modyfikuj($device, $_POST['opis_historii']);
		if($server->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Uaktualniono serwer.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono serwera!");
		}
	break;
	case 'Bramka_voip':
		$bramka = new Bramka_voip();
		$sql = $bramka->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$bramka->modyfikuj($device, 
			$_POST['sn'], 
			$_POST['producent'], 
			$_POST['model'], 
			$_POST['opis_historii']);
		if($bramka->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Uaktualniono bramkę VoIP.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono bramki VoIP!");
		}
	break;
	case 'Router':
		$router = new Router();
		$sql = $router->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$router->modyfikuj($device, 
			$_POST['opis_historii']);
		if($router->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("uaktualniono router.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono routera!");
		}
	break;
	case 'Host':
		$host = new Host();
		$sql = $host->connect();
                $daddy = new Daddy();
                $mac_changed = $daddy->hostMacChanged($device['dev_id'], $device['mac']);
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$host->modyfikuj($device, 
			$_POST['nr_mieszkania'],
			$_POST['pakiet'], 
			$_POST['data_uruchomienia'], 
			$_POST['con_id'], 
			$_POST['opis_historii'],
			$_POST['data_zakonczenia']); 
		if($host->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("uaktualniono hosta.");
                        if($_POST['con_id'] && $mac_changed)
                        {
                          require(MYMYSQL_FILE);
                          require(CONNECTIONS_FILE);
                          $_SESSION['permissions']=2;
                          $con = new Connections();
                          if($con->update($_POST['con_id'], 'mac', $device['mac'], null))
			    Daddy::error("uaktualniono listę podłączeń");
                        }  
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono hosta!");
		}
	break;
	case 'Virtual':
		$virtual = new Device();
		$sql = $virtual->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$virtual->modyfikuj_virtual($device);
		if($virtual->mac == TRUE)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("uaktualniono virtual.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie uaktualniono virtual!");
		}
	break;
	case 'Switch_rejon':
		$switch = new Switch_rejon();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->modyfikuj($device, 
			$_POST['opis_historii']);
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Zmodyfikowanu przełącznik rejonowy.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie zmodyfikowano przełącznika!");
		}
	break;
	case 'Switch_centralny':
		$switch = new Switch_rejon();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->modyfikuj($device, 
			$_POST['opis_historii']);
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Zmodyfikowanu przełącznik rejonowy.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie zmodyfikowano przełącznika!");
		}
	break;
	}
        if($device['device_type']=='Virtual' && $device['lokalizacja']=='11111')
	  echo"<center><a href=\"index.php?device=".$device['dev_id']."\">Powrót</a></center>";
        else
	  echo"<center><a href=\"modyfikuj.php?device=".$device['dev_id']."\">Powrót</a></center>";
}
elseif (isset($_GET['device']))
{

	//generujemy listę urządzeń
	$zapytanie = "SELECT Device.dev_id, Device.mac, Adres_ip.ip, Device.device_type, Device.other_name, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka 
		FROM Lokalizacja, Device 
		LEFT JOIN Adres_ip ON (Adres_ip.device=Device.dev_id AND Adres_ip.main='1') 
		WHERE Device.lokalizacja = Lokalizacja.id  AND Lokalizacja.osiedle!='MAGAZYN' AND (Device.device_type='Switch_rejon' OR Device.device_type='Switch_bud' OR Device.device_type='Router' OR Device.device_type='switch_centralny' OR Device.device_type='specjalne') 
		ORDER BY Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka";
	$obj = new Device();
	$sql = $obj->connect();
	$wynik = $obj->query($zapytanie);
	$opcje = array();
	for($i=0; $i < count($wynik); $i++)
	{
		$opcje[$i]['dev_id'] = $wynik[$i]['dev_id'];
		if($wynik[$i]['other_name'])
			$opcje[$i]['nazwa'] = $wynik[$i]['other_name']." - ".$wynik[$i]['ip'];
		else if($wynik[$i]['device_type']=='Switch_rejon')
			$opcje[$i]['nazwa'] = $wynik[$i]['osiedle']. $wynik[$i]['nr_bloku'].$wynik[$i]['klatka']." - ".$wynik[$i]['ip']."(R)";
		else
			$opcje[$i]['nazwa'] = $wynik[$i]['osiedle']. $wynik[$i]['nr_bloku'].$wynik[$i]['klatka']." - ".$wynik[$i]['ip'];
	}
	$dev_id = mysql_real_escape_string($_GET['device']);
//pobieranie adresów ip
	$zapytanie = "SELECT * FROM Adres_ip WHERE Adres_ip.device='$dev_id'";
	$adresy = $obj->query($zapytanie);
	if ($adresy)
		foreach($adresy as $key=>$adres)
		{
			$zapytanie = "SELECT vlan FROM Podsiec WHERE id='".$adres['podsiec']."'";
			$adresy[$key]['vlan'] = $obj->query($zapytanie);
		}
//pobieranie portów
	$zapytanie = "SELECT parent_port FROM Agregacja WHERE device='$dev_id'";
	$porty = $obj->query($zapytanie);
	$parent_ports;
	if($porty)
		foreach($porty as $port)
		{
			if(is_array($port))
			{
				if($parent_ports)
					$parent_ports = $parent_ports.";".$port[0];	
				else
					$parent_ports = $port[0];
			}
			else
			{
				$parent_ports = $port;
			}
		}
	$zapytanie = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
	$device_type = $obj->query($zapytanie);
	$device_type = $device_type[0];
	require('formularz_naglowek.php');
	switch ($device_type)
	{
	case 'Switch_rejon':
		$zapytanie = "SELECT * FROM Device INNER JOIN Switch_rejon ON Switch_rejon.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
//		print_r($device);
		require('formularz_device.php');
		require('formularz_switch_rejon.php');
		break;
	case 'Switch_bud':
		$zapytanie = "SELECT * FROM Device INNER JOIN Switch_bud ON Switch_bud.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_switch_bud.php');
		break;
	case 'Kamera':
		$zapytanie = "SELECT * FROM Device INNER JOIN Kamera ON Kamera.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_kamera.php');
		break;
	case 'Serwer':
		$zapytanie = "SELECT * FROM Device INNER JOIN Serwer ON Serwer.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_serwer.php');
		break;
	case 'Bramka_voip':
		$zapytanie = "SELECT * FROM Device INNER JOIN Bramka_voip ON Bramka_voip.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_bramka_voip.php');
		break;
	case 'Host':
		$zapytanie = "SELECT *,DATE_FORMAT(data_uruchomienia, '%d.%m.%y') AS start, DATE_FORMAT(data_zakonczenia, '%d.%m.%y') AS stop FROM Device, Lokalizacja, Agregacja, Host WHERE Device.dev_id='$dev_id' AND Device.lokalizacja=Lokalizacja.id AND Host.device='$dev_id' AND Agregacja.device=Device.dev_id LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_host.php');
		break;
	case 'Router':
		$zapytanie = "SELECT * FROM Device, Lokalizacja, Router, Agregacja WHERE Device.dev_id='$dev_id' AND Device.lokalizacja=Lokalizacja.id AND Router.device='$dev_id' AND Agregacja.device=Device.dev_id LIMIT 1";
		$device = $obj->query($zapytanie);
		require('formularz_device.php');
		require('formularz_router.php');
		break;
	case 'Virtual':
		$zapytanie = "SELECT * FROM Device 
			LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id
			LEFT JOIN Lokalizacja ON Lokalizacja.id=Device.lokalizacja
			WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query_assoc($zapytanie);
		require('formularz_device.php');
		break;
	case 'Switch_centralny':
		$zapytanie = "SELECT * FROM Device INNER JOIN Switch_rejon ON Switch_rejon.device=Device.dev_id LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id WHERE Device.dev_id='$dev_id' LIMIT 1";
		$device = $obj->query($zapytanie);
//		print_r($device);
		require('formularz_device.php');
		require('formularz_switch_rejon.php');
		break;
}
	require('formularz_stopka.php');
}
?>

