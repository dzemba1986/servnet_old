<?php
require("security.php");
if($_POST['find_phrase'])
{
	require('include/definitions.php');
	$daddy = new Daddy();
	$daddy->connect();
	$fraza = mysql_real_escape_string($_POST['find_phrase']);
        $fraza = trim($fraza);
	$tryb_wyszukiwania = $_POST['find_mode'];
	$title = "Szukana fraza: \"$fraza\"";	
}
?>
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">

<title>Wyszukiwanie urządzeń</title>
  <link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/dodaj.css" type="text/css" />
  <link rel="stylesheet" href="css/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
	
</head>
<body>
<div id="cialo">
<div id="naglowek"><?php include('menu.php') ?></div>
<b>Szukaj urządzenia</b>
<div id="formiu">
	<form method="post">
	<br><select name="find_mode">
		<option value="lok" <?php if($tryb_wyszukiwania=='lok') echo 'selected'; ?>>Lokalizacja/Nazwa</option>
		<option value="ip" <?php if($tryb_wyszukiwania=='ip') echo 'selected'; ?>>Adres IP</option>
		<option value="mac" <?php if($tryb_wyszukiwania=='mac') echo 'selected'; ?>>Adres MAC</option>
		<option value="id" <?php if($tryb_wyszukiwania=='id') echo 'selected'; ?>>ID</option></select>
	<input type="text" name="find_phrase">
	<input type="submit" value="Szukaj">
	</form>
</div>
<div id="wyniki">
<?php
if($fraza && $tryb_wyszukiwania)
{
	echo $title;
	$zapytanie;
	switch($tryb_wyszukiwania)
	{
		case 'ip':
		$zapytanie = "SELECT Adres_ip.ip as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
			FROM Adres_ip 
			LEFT JOIN Device ON Adres_ip.device=Device.dev_id 
			LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
			LEFT JOIN Host ON Host.device=Adres_ip.device 
			WHERE Adres_ip.ip LIKE '%$fraza%' 
			ORDER BY Adres_ip.ip ASC";
		break;
		case 'mac':
		$zapytanie = "SELECT Device.mac as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania FROM Device LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id LEFT JOIN Host ON Host.device=Device.dev_id WHERE Device.mac LIKE '%$fraza%' ORDER BY Device.mac ASC";
		break;
		case 'lok':
		$zapytanie = "SELECT * FROM ((
			SELECT Device.other_name as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
			FROM Device 
			LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
			LEFT JOIN Host ON Host.device=Device.dev_id 
			WHERE Device.other_name LIKE '%$fraza%') 
			UNION(
			SELECT CONCAT(Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka) as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
			FROM Device 
			LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
			LEFT JOIN Host ON Host.device=Device.dev_id) 
			UNION (
			SELECT CONCAT(Lokalizacja.osiedle, Lokalizacja.nr_bloku, '/', Host.nr_mieszkania) as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
			FROM Device 
			LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
			LEFT JOIN Host ON Host.device=Device.dev_id)) a 
			WHERE fraza LIKE '%$fraza%' ORDER BY fraza ASC";
		break;
		case 'id':
		$zapytanie = "SELECT Device.dev_id as fraza, Device.dev_id, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
			FROM Device 
			LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
			LEFT JOIN Host ON Host.device=Device.dev_id 
			WHERE Device.dev_id='$fraza' 
			ORDER BY Device.dev_id ASC";
		break;
	}
	$wynik = $daddy->query_assoc_array($zapytanie);
	echo"<table border=\"1\"><tr><td>pasujące frazy</td><td>urządzenie</td></tr>";
	if($wynik)
	foreach($wynik as $wiersz)
	{	
		$rekord = $wiersz;
		if($rekord['device_type']=="Host")	
			echo"<tr><td>".$rekord['fraza']."</td><td><a href=\"tree.php?device=".$rekord['dev_id']."\">".$rekord['osiedle'].$rekord['nr_bloku'].$rekord['klatka']."/".$rekord['nr_mieszkania']."(".$rekord['device_type'].")</a></td></tr>";
		elseif($rekord['other_name'])	
			echo"<tr><td>".$rekord['fraza']."</td><td><a href=\"tree.php?device=".$rekord['dev_id']."\">".$rekord['other_name']."(".$rekord['device_type'].")</a></td></tr>";
		elseif($rekord['osiedle']=="MAGAZYN")
			echo"<tr><td>".$rekord['fraza']."</td><td><a href=\"modyfikuj.php?device=".$rekord['dev_id']."\">".$rekord['osiedle'].$rekord['nr_bloku'].$rekord['klatka']."(".$rekord['device_type'].")</a></td></tr>";
		else
			echo"<tr><td>".$rekord['fraza']."</td><td><a href=\"tree.php?device=".$rekord['dev_id']."\">".$rekord['osiedle'].$rekord['nr_bloku'].$rekord['klatka']."(".$rekord['device_type'].")</a></td></tr>";
	}
	echo"</table>";
}
?>
</div>
<div id="stopka"></div>
</div>
</body>
</html>
