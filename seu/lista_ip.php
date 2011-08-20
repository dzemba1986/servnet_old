<?php
require("security.php");
if($_POST['_vlan0'] && $_POST['_podsiec0'])
{
	require('include/definitions.php');
	$daddy = new Daddy();
	$daddy->connect();
	$podsiec = intval($_POST['_podsiec0']);
	$wolne_wyswietlaj = $_POST['wolne'];
	$zapytanie2="SELECT * FROM Podsiec WHERE id='$podsiec'";
	$wynik2 = $daddy->query_assoc($zapytanie2) or die("nie ma takiej podsieci w bazie!");
	$title = "Vlan: ".$wynik2['vlan'].", Podsieć: ".$wynik2['address']."/".$wynik2['netmask']." (".$wynik2['opis'].")";	
}
?>
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">

<title>Adresy IP</title>
  <link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/dodaj.css" type="text/css" />
  <link rel="stylesheet" href="css/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
<script language="JavaScript" SRC="js/vlan.js"></script>
<script language="JavaScript" SRC="js/dodaj.js"></script>
	
</head>
<body>
<div id="cialo">
<div id="naglowek"><?php include('menu.php') ?></div>
<b>Adresy IP</b>
<div id="formiu">
	<form method="post">
	<div id="vlans"></div>
	<br>Wolne adresy<select name="wolne"><option value="">nie wyświetlaj</option>
					     <option value="1" <?php if($_POST['wolne']) echo "selected"; ?>>wyświetlaj</option></select>
	<input type="submit" value="wygeneruj">
	</form>
	<script type="text/javascript">
		IPLISTA = true;
		VLAN = <?php if($_POST['_vlan0']) echo $_POST['_vlan0']; else echo 'false'; ?>;
		PODSIEC =  <?php if($_POST['_podsiec0']) echo $_POST['_podsiec0']; else echo 'false'; ?>;
		dodajPole();
	</script>
</div>
<div id="adresy">
<?php
echo $title;
if($_POST['_vlan0'] && $_POST['_podsiec0'])
{
	$zapytanie = "SELECT Adres_ip.ip, Adres_ip.podsiec, Adres_ip.device, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Device.device_type, Host.nr_mieszkania 
		FROM Adres_ip 
		LEFT JOIN Device ON Adres_ip.device=Device.dev_id 
		LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
		LEFT JOIN Host ON Host.device=Adres_ip.device 
		WHERE Adres_ip.podsiec='$podsiec'";
	$wynik = $daddy->query_assoc_array($zapytanie);
	$podsiec_obj = new IpAddress ($wynik2['address'], $wynik2['netmask']);
	$adresy = $podsiec_obj->generujPodsiec();
	echo"<table border=\"1\"><tr><td>adres</td><td>urzadzenie</td></tr>";
	foreach($adresy as $wiersz)
	{	
		$baza_id = false;
		if(is_array($wynik))
		foreach($wynik as $id=>$adres_baza)
		{
			if($wiersz == $adres_baza['ip'])
			{
				$baza_id = $id;
				break;
			}
		}
		if($baza_id!==false)
		{
			$rekord = $wynik[$baza_id];
			if($rekord['device_type']=="Host")	
				echo"<tr><td>".$wiersz."</td><td><a href=\"index.php?device=".$rekord['device']."\">".$rekord['osiedle'].$rekord['nr_bloku'].$rekord['klatka']."/".$rekord['nr_mieszkania']."(".$rekord['device_type'].")</a></td></tr>";
                        elseif($rekord['device_type']=="Virtual" && $rekord['osiedle']=='Virtual')	
				echo"<tr><td>".$wiersz."</td><td><a href=\"modyfikuj.php?device=".$rekord['device']."\">".$rekord['other_name']."(".$rekord['device_type'].")</a></td></tr>";
			elseif($rekord['other_name'])	
				echo"<tr><td>".$wiersz."</td><td><a href=\"index.php?device=".$rekord['device']."\">".$rekord['other_name']."(".$rekord['device_type'].")</a></td></tr>";
			else
				echo"<tr><td>".$wiersz."</td><td><a href=\"index.php?device=".$rekord['device']."\">".$rekord['osiedle'].$rekord['nr_bloku'].$rekord['klatka']."(".$rekord['device_type'].")</a></td></tr>";
		}
		elseif($wolne_wyswietlaj)
			echo"<tr><td>".$wiersz."</td><td> -- </td></tr>";
	}
	echo"</table>";
}
?>
</div>
<div id="stopka"></div>
</div>
</body>
</html>
