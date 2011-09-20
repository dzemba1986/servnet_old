<?php
require("security.php");
//require("include/formDuplicat.php");
require("include/definitions.php");
if(isset($_REQUEST['device_type']))
	$device_type = $_REQUEST['device_type'];

if(isset($_POST['dodaj']))
{
	$device = array(
		'mac' => $_POST['mac'],
		'exists' => $_POST['exists'],
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
	$device['ip'] = $ip;
	$obj;
}
elseif($_SESSION['popraw'])
{
	echo"zle";
	$device = $_SESSION['device'];
	$device_type = $device['device_type'];
	$device['nr_bloku'] = $device['blok'];
	unset($_SESSION['popraw']);
	unset($_SESSION['device']);
	$_POST['sn'] = $device['sn'];
	$_POST['model'] = $device['model'];
	$_POST['producent'] = $device['producent'];
	$_POST['opis_historii'] = $device['opis_historii'];
}
if(isset($_POST['dodaj']))
{
			$device['sn'] = $_POST['sn'];
			$device['model'] = $_POST['model'];
			$device['producent'] = $_POST['producent'];
			$device['opis_historii'] = $_POST['opis_historii'];
	if($device['device_type']!="Virtual")
		$obj = new $device['device_type'] ();
	$dev_obj = new Device();
	if(! $dev_obj->sprawdzIp($ip, $device_type=="Virtual") ||! $dev_obj->sprawdzDevice($device))
	{
			$_SESSION['popraw'] = true;
			$_SESSION['device'] = $device;
			echo "<a href=\"dodaj.php\">Popraw</a>";	

		exit();
	}
        $dev_id = null;
	switch($device['device_type'])
	{
	case 'Switch_rejon':
		$switch = new Switch_rejon();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		//trzeba włączyć zmienne 'sn' itd do tablicy device
		$switch->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'], 
			$_POST['opis_historii']);
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano przełącznik rejonowy.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano przełącznika!");
			$_SESSION['popraw'] = true;
			$_SESSION['device'] = $device;
			echo "<a href=\"dodaj.php\">Popraw</a>";	
		}
	break;
	case 'Switch_bud':
		$switch = new Switch_bud();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'],
			$_POST['typ'],
			$_POST['opis_historii']);
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano przełącznik.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano przełącznika!");
		}
	break;
	case 'Kamera':
		$kamera = new Kamera();
		$sql = $kamera->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$kamera->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'],
			$_POST['opis_historii']);
		if($kamera->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano kamerę.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano kamery!");
		}
	break;
	case 'Serwer':
		$server = new Serwer();
		$sql = $server->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$server->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'], 
			$_POST['opis_historii']);
		if($server->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano serwer.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano serwera!");
		}
	break;
	case 'Bramka_voip':
		$bramka = new Bramka_voip();
		$sql = $bramka->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$bramka->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'], 
			$_POST['opis_historii']);
		if($bramka->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano bramkę VoIP.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano bramki VoIP!");
		}
	break;
	case 'Router':
		$router = new Router();
		$sql = $router->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$router->dodaj($device, 
			$_POST['sn'], 
			$_POST['model'], 
			$_POST['producent'],
			$_POST['opis_historii']);
		if($router->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano router.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano routera!");
		}
	break;
	case 'Host':
		$host = new Host();
		$sql = $host->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$host->dodaj($device, 
			 $_POST['nr_mieszkania'],
			 $_POST['pakiet'],
			 $_POST['data_uruchomienia'], 
			 $_POST['con_id'], 
			 $_POST['opis_historii'],
			 $_POST['data_zakonczenia']);
		if($host->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano hosta.");
                        $dev_id = $host->device->dev_id;
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano hosta!");
		}
	break;
	case 'Virtual':
		$virtual = new Device();
		$sql = $virtual->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$virtual->dodaj_virtual($device); 
		if($virtual->mac == TRUE)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano virtuala.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano virtuala!");
		}
	break;
	}
        if(!$dev_id)
          $dev_id = $_POST['parent_device'];
	echo"<center><a href=\"index.php?device=$dev_id\">Powrót</a></center>";
}
else
{
	//generujemy listę urządzeń
	$zapytanie = "SELECT Device.dev_id, Device.mac, Adres_ip.ip, Device.device_type, Device.other_name, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka 
		FROM Lokalizacja, Device 
		LEFT JOIN Adres_ip ON (Adres_ip.device=Device.dev_id AND Adres_ip.main='1') 
		WHERE Device.lokalizacja = Lokalizacja.id  AND Lokalizacja.osiedle!='MAGAZYN' AND (Device.device_type='Switch_rejon' OR Device.device_type='Switch_bud' OR Device.device_type='Router' OR Device.device_type='switch_centralny' OR Device.device_type='specjalne') 
		ORDER BY Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka";
	$obj = new Device();
	$sql = $obj->connect();
	$wynik = $obj->query_assoc_array($zapytanie);
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
//	print_r($wynik);
	require('formularz_naglowek.php');
        if($device_type)
        {
          require('formularz_device.php');
          switch ($device_type)
          {
            case 'Switch_rejon':
                    require('formularz_switch_rejon.php');
                    break;
            case 'Switch_bud':
                    require('formularz_switch_bud.php');
                    break;
            case 'Kamera':
                    require('formularz_kamera.php');
                    break;
            case 'Serwer':
                    require('formularz_serwer.php');
                    break;
            case 'Bramka_voip':
                    require('formularz_bramka_voip.php');
                    break;
            case 'Host':
                    require('formularz_host.php');
                    break;
            case 'Router':
                    require('formularz_router.php');
                    break;
          }
        }   
	require('formularz_stopka.php');
}
?>
