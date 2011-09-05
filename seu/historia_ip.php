<?php
require("security.php");
if($_POST['find_phrase'])
{
	require('include/definitions.php');
	$daddy = new Daddy();
	$daddy->connect();
	$fraza = mysql_real_escape_string(trim($_POST['find_phrase']));
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

<title>Wyszukiwanie historii abonentów</title>
<link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/black/naglowek.css" type="text/css" />
<link rel="stylesheet" href="css/black/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
	
</head>
<body>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
<b>Szukaj Hosta</b>
<div id="formiu">
	<form method="post">
	<br><select name="find_mode">
		<option value="ip">Adres IP</option>
		<option value="lok" <?php if($_POST['find_mode']=='lok') echo "selected"; ?>>Lokalizacja/Nazwa</option>
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
		$zapytanie = "SELECT *, CONCAT(osiedle, ' ', blok, '/', mieszkanie) as lokalizacja 
			FROM Historia_ip
			WHERE ip LIKE '%$fraza%' 
			ORDER BY ip ASC";
		break;
		case 'lok':
		$zapytanie = "SELECT * FROM (
			SELECT *, CONCAT(osiedle, ' ', blok, '/', mieszkanie) as lokalizacja
			FROM Historia_ip) a
			WHERE lokalizacja LIKE '%$fraza%' ORDER BY lokalizacja ASC";
		break;
	}
	$wynik = $daddy->query_assoc_array($zapytanie);
	echo"<table class=\"opis\" style=\"width:500px; margin-bottom:20px;\"><tr style=\"background:#888;\"><td>Lokalizacja</td><td>IP</td><td>od</td><td>do</td></tr>";
	if($wynik)
	foreach($wynik as $wiersz)
	{	
		$rekord = $wiersz;
		echo"<tr><td>".$rekord['lokalizacja']."</td><td>".$rekord['ip']."</td><td>".$rekord['data_od']."</td><td>".$rekord['data_do']."</td></td></tr>";
	}
	echo"</table>";
}
?>
</div>
</div>
<div id="dol"></div>
</div>
</body>
</html>
