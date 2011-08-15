<?php
require("security.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link rel="stylesheet" href="css/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Wymiana urządzenia</title>

  <link rel="stylesheet" href="css/styles.css" type="text/css" />
</head>
<body>
<div id="cialo">
<div id="naglowek"><?php include('menu.php') ?></div>
<?php
if(!$_GET['dev_id'])
	die('Nieprawidłowe wywołanie formularza!');
require('include/definitions.php');
$daddy = new Daddy();
$dev_id =$_GET['dev_id']; 
$device_type = $daddy->getType($dev_id);
echo "Wymień urządzenie o dev_id=$dev_id na:";
switch($device)
{
	case "Host":
		die('hostów sie nie wymienia');
		break;
	default:
		$zapytanie = "SELECT Device.dev_id, Device.mac, Producent.name as producent, Model.name as model, $device_type.sn, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka
			FROM Device
			LEFT JOIN Lokalizacja ON Lokalizacja.id=Device.lokalizacja
			LEFT JOIN $device_type ON $device_type.device=Device.dev_id
			LEFT JOIN Producent ON Producent.id=$device_type.producent
			LEFT JOIN Model ON Model.id=$device_type.model
			WHERE Device.device_type='$device_type' AND Model.id=$device_type.model AND Device.dev_id!='$dev_id' ORDER BY Lokalizacja.osiedle";
		$lista = $daddy->query_assoc_array($zapytanie);
		echo"<br><form action=\"wymien_porty.php\" method=\"post\"><select name=\"po\">";
		foreach($lista as $wiersz)
		{
			echo "<option value=\"".$wiersz['dev_id']."\">".$wiersz['producent']." ".$wiersz['model']." - ".$wiersz['sn']." - ".$wiersz['mac']." - ".$wiersz['osiedle'].$wiersz['nr_bloku'].$wiersz['klatka']."</option>";
			echo"<br>";
		}
		echo"</select><input type=\"hidden\" name=\"przed\" value=\"$dev_id\"><input type=\"submit\" value=\"wybierz\"></form>";
 		break;
}
