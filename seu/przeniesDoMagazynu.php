<?php
require("security.php");
require('include/definitions.php');
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
	
</head>
<body>

<?php
if($_GET['dev_id'])
{
	$dev_id = $_GET['dev_id'];
	if($_GET['confirm'])
	{
		$device = new Device();
		$device->usunDoMagazynu($_GET['dev_id']);
		echo"<center><a href=\"tree.php\">Powrót</a></center>";
	}
	else
	{
	echo"<center>Czy na pewno chcesz przenieść urządzenie o id $dev_id do magazynu? <br>Wiąże się to z usunięciem informacji o adresach IP i łączach z wiązanych z tym urządzeniem!!! 
			<form method=\"get\">
				<input type=\"submit\" value=\"Tak\">
				<input type=\"button\" value=\"Nie\" Onclick=\"window.history.back(-1);\">
				<input type=\"hidden\" name=\"dev_id\" value=\"$dev_id\">		
				<input type=\"hidden\" name=\"confirm\" value=\"1\">	
			</form></center>";	
	}
echo "</body></html>";
}
