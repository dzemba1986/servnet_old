<?php
require("security.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
<link rel="stylesheet" href="css/black/naglowek.css" type="text/css" />
<link rel="stylesheet" href="css/black/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Wymiana urządzenia - przepisanie portów</title>

  <link rel="stylesheet" href="css/styles.css" type="text/css" />
</head>
<body>

<?php 
if($_POST['zamien']=="zamień")
{

	require('include/definitions.php');
	$porty_nowe = $_POST['porty'];
	$dev_id_1 = $_POST['dev_id_1'];
	$dev_id_2 = $_POST['dev_id_2'];
	$device = new Device();
	$device->wymien($porty_nowe, $dev_id_1, $dev_id_2);
	exit();
}
	echo"<center><a href=\"index.php?device=$dev_id_1\">Powrót</a></center>";

?>

<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
<?php
if(!$_POST['po'])
	die('Nieprawidłowe wywołanie formularza!');
require('include/definitions.php');
$daddy = new Daddy();
$przed = $_POST['przed']; 
$po = $_POST['po']; 
$device_type = $daddy->getType($przed);
$zapytanie = "SELECT Model.id, Model.name as model, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Adres_ip.ip FROM Model, $device_type, Lokalizacja, Device LEFT JOIN Adres_ip ON (Adres_ip.device=Device.dev_id AND Adres_ip.main='1') WHERE Device.dev_id='$przed' AND $device_type.device=Device.dev_id AND Model.id=$device_type.model AND Lokalizacja.id=Device.lokalizacja ";
$device_przed = $daddy->query_assoc($zapytanie);
$zapytanie = "SELECT Model.id, Model.name as model, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Adres_ip.ip 
	FROM Device 
	LEFT JOIN Lokalizacja ON Lokalizacja.id=Device.lokalizacja
	LEFT JOIN $device_type ON $device_type.device=Device.dev_id
	LEFT JOIN Model ON Model.id=$device_type.model
	LEFT JOIN Adres_ip ON (Adres_ip.device=Device.dev_id AND Adres_ip.main='1')
	WHERE Device.dev_id='$po'";
$device_po = $daddy->query_assoc($zapytanie);
$porty = $daddy->getModelPortList($device_przed['id']);
$porty_po = $daddy->getModelPortList($device_po['id']);


$porty_po_options = "<option value=\"\"></option>";
foreach($porty_po as $key=>$port)
{
	$nazwa = generateName($port, $po);
	$porty_po_options = $porty_po_options."<option value=\"".$port."\">".$port." - $nazwa</option>";
}





$port_przed = array();
$port_po = array();
//Echo $przed." ".$po."<br>";
echo"<br>Konieczne jest przepisanie wszystkich wykorzystywanych portów zarówno na urządzeniu 1 jak i 2.<br>";
echo"<form method=\"post\"><input type=\"hidden\" name=\"dev_id_1\" value=\"$przed\"><input type=\"hidden\" name=\"dev_id_2\" value=\"$po\">";
echo"<table border=\"1\" bordercolor=\"black\" cellspacing=\"0\">";
echo"<tr><td>Porty urządzenia 1</td><td>Podłączone urządzenia</td><td>Porty urządzenia 2</td>";
foreach($porty as $key=>$port)
{
	$nazwa = generateName($port, $przed);
echo"<tr>
	<td>$port</td>
	<td>$nazwa</td>
	<td><select name=\"porty[$port]\">";
		echo $porty_po_options;
	echo"</select></td>
    </tr>";
}
echo"</table><input type=\"submit\" name=\"zamien\" value=\"zamień\"></form>";


function generateName($port, $dev)
{
	//najpierw należy sprawdzić czy na tym porcie podłączone jest jakieś urządzenie
	$zapytanie = "SELECT parent_device FROM Agregacja WHERE device='$dev' AND local_port='$port' UNION
		SELECT device FROM Agregacja WHERE parent_device='$dev' AND parent_port='$port'";
//echo $zapytanie;
	$daddy = new Daddy();
	$wynik = $daddy->query_assoc_array($zapytanie);
	//echo"<br>$zapytanie<br>";
	if(!count($wynik))
		return null;
	elseif(count($wynik)>1)
		die("zdublowany port $port na urządzeniu $dev!");
	$connected_dev = $wynik[0]['parent_device'];
	$device_type = $daddy->getType($connected_dev);
	if($device_type=="Switch_centralny") 
		$device_type="Switch_rejon";
	$zapytanie;
	if($device_type=="Host")
		$zapytanie = "SELECT DISTINCT Lokalizacja.osiedle, null AS model, Lokalizacja.nr_bloku, Adres_ip.ip, CONCAT('/',Host.nr_mieszkania) AS klatka 
			FROM Model, Host, Lokalizacja, Adres_ip, Device 
			WHERE Device.dev_id='$connected_dev' AND Host.device=Device.dev_id AND Lokalizacja.id=Device.lokalizacja AND Adres_ip.device=Device.dev_id AND Adres_ip.main='1'";
	else
		$zapytanie = "SELECT DISTINCT Model.name as model, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Adres_ip.ip FROM Model, $device_type, Lokalizacja, Device LEFT JOIN Adres_ip ON Adres_ip.device=Device.dev_id AND Adres_ip.main='1' WHERE Device.dev_id='$connected_dev' AND $device_type.device=Device.dev_id AND Model.id=$device_type.model AND Lokalizacja.id=Device.lokalizacja";
	$wynik = $daddy->query_assoc($zapytanie);
	$nazwa = $wynik['model']." <b>".$wynik['osiedle'].$wynik['nr_bloku'].$wynik['klatka']."</b> ".$wynik['ip'];
	return $nazwa;
}
?>
</div>
<div id="dol"></div>
</div>
</body>
</html>
