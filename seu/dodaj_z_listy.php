<?php
require("security.php");
//require("include/formDuplicat.php");
require("include/definitions.php");
$device_type = 'Host';

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
		'uplink_mc_sn' => $_POST['uplink_mc_sn'],
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
//			$_SESSION['popraw'] = true;
//			$_SESSION['device'] = $device;
//			echo "<a href=\"dodaj.php\">Popraw</a>";	

		exit();
	}
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
        }
        else
        {
                mysql_query("ROLLBACK", $sql) or die(mysql_error());
                Daddy::error("Nie dodano hosta!");
        }
	echo"<center><a href=\"tree.php?device=".$_POST['parent_device']."\">Powrót</a></center>";
}
else
{
  //****************************************************************************
  // Wykorzystywane dane z innej bazy
  //**********************************
  $con_id = $_GET['con_id'];
  if(!$con_id)
   die('Błąd przesyłania identyfikatora z Listy podłączeń!!');
  require(ROOT.'/lista/include/classes/mysql.php');
  $con_id = intval($con_id);
  $con_sql = new myMysql();
  $query = "SELECT c.mac, c.switch as parent_device, c.switch_loc, c.port as parent_port, l.ulic as osiedle, l.blok as nr_bloku, l.nazwa_inna as other_name, CONCAT(l.mieszkanie, l.klatka) as nr_mieszkania, c.speed as pakiet FROM Connections c LEFT JOIN Lokalizacja l ON c.localization=l.id WHERE c.id='$con_id'";
  $device = $con_sql->query($query);
  if(!$device)
    die("Obiekt o id $con_id nie istnieje w bazie listy podłącze!");
  $device['con_id'] = $con_id;
  $query = "SELECT d.dev_id FROM Device d, Lokalizacja l WHERE l.id LIKE '".$device['switch_loc']."' AND d.device_type='Switch_bud' AND d.lokalizacja=l.id";
  $sql = new Daddy();
  $parent_device = $sql->query_assoc_array($query);
  $parent_device = $parent_device[0]['dev_id'];
  if($parent_device)
    $device['parent_device'] = $parent_device;

  //****************************************************************************
	//generujemy listę urządzeń
	$zapytanie = "SELECT Device.dev_id, Device.mac, Adres_ip.ip, Device.device_type, Device.other_name, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka 
		FROM Lokalizacja, Device 
		LEFT JOIN Adres_ip ON (Adres_ip.device=Device.dev_id AND Adres_ip.main='1') 
		WHERE Device.lokalizacja = Lokalizacja.id  AND Lokalizacja.osiedle!='MAGAZYN' AND Device.device_type='Switch_bud'
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
	require('formularz_device_z_listy.php');
        require('formularz_host.php');
	require('formularz_stopka.php');
}
?>
