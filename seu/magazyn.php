<?php
require("security.php");
require("include/formDuplicat.php");
require("include/definitions.php");
$daddy = new Daddy();
if($_POST['dodaj_do_magazynu'] && $_POST['producent'] && $_POST['model'] && $_POST['device_type'])
{
	$sql = $daddy->connect();
	$mac = mysql_real_escape_string($_POST['mac']);
        $mac = strtolower($mac);
	$sn = mysql_real_escape_string($_POST['sn']);
	$producent = mysql_real_escape_string($_POST['producent']);
	$device_type = mysql_real_escape_string($_POST['device_type']);
	$model = mysql_real_escape_string($_POST['model']);
//	$daddy->dodajDoMagazynu($mac, $sn, $model);

	$device = array(
		'mac' => $mac,
		'device_type' => $device_type);
	$device['ip'] = $ip;
	$obj = new Device();
	switch($device_type)
	{
	case 'Switch_rejon':
		$switch = new Switch_rejon();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
		if($switch->device != -1)
		{
			mysql_query("COMMIT", $sql) or die(mysql_error());
			Daddy::error("Dodano przełącznik rejonowy.");
		}
		else
		{
			mysql_query("ROLLBACK", $sql) or die(mysql_error());
			Daddy::error("Nie dodano przełącznika!");
		}
	break;
	case 'Switch_bud':
		$switch = new Switch_bud();
		$sql = $switch->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$switch->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
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
		echo "kamere";
		$kamera = new Kamera();
		$sql = $kamera->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$kamera->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
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
		$server = new Server();
		$sql = $server->connect();
		mysql_query("SET AUTOCOMMIT=0", $sql);
		mysql_query("BEGIN", $sql);
		$server->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
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
		$bramka->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
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
		$router->dodaj_do_magazynu($device, 
			$sn, 
			$model, 
			$producent); 
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
	}
}
elseif(isset($_POST['dodaj']))
{
        if($_POST['dev_id'])
	{
		$device = new Device();
		$device->modyfikujMagazyn($_POST['dev_id'], $_POST['other_name'], $_POST['opis'], $_POST['opis_historii']);
		unset($device);
	}	
}
require("formularz_magazyn.php");
?>
