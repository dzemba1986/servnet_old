<?php require("security.php"); ?>
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">

<title><? if(!isset($_GET['device']))
 	echo"Dodawanie urządzeń";
else
	echo"Edycja urządzenia";
?> </title>
<link rel="stylesheet" href="css/black/naglowek.css" type="text/css" />
<link rel="stylesheet" href="css/black/dodaj.css" type="text/css" />
<link rel="stylesheet" href="css/black/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
<script language="JavaScript" SRC="js/xml.js"></script>
<script language="JavaScript" SRC="js/producentForm.js"></script>
<script language="JavaScript" SRC="js/modelForm.js"></script>
<script language="JavaScript" SRC="js/portForm.js"></script>
<script language="JavaScript" SRC="js/uplink.js"></script>
<? if(!isset($_GET['device']))
	echo("<script language=\"JavaScript\" SRC=\"js/dodaj.js\"></script>");
  else
{
	echo("<script language=\"JavaScript\" SRC=\"js/modyfikuj.js\"></script>");
	echo("<script language=\"JavaScript\" SRC=\"js/ajax_base.js\"></script>");
	echo("<script language=\"JavaScript\" SRC=\"js/changeMac.js\"></script>");
}
        ?>
	
</head>
<body>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
<div id="formularz">
<? if(!isset($_GET['device']))
{	?>
<form method="post" action="">
<select name="device_type"> 
		<option value="Switch_bud" <?php if($device_type=="Switch_bud")echo("selected")?>>switch budynkowy</option>
		<option value="Switch_rejon" <?php if($device_type=="Switch_rejon")echo("selected")?>>switch rejonowy</option>
		<option value="Kamera" <?php if($device_type=="Kamera")echo("selected")?>>kamera</option>
		<option value="Host" <?php if($device_type=="Host")echo("selected")?>>host</option>
		<option value="Serwer" <?php if($device_type=="Serwer")echo("selected")?>>serwer</option>
		<option value="Router" <?php if($device_type=="Router")echo("selected")?>>router</option>
		<option value="Bramka_voip" <?php if($device_type=="Bramka_voip")echo("selected")?>>bramka VoIP</option>
		<option value="Virtual" <?php if($device_type=="Virtual")echo("selected")?>>Virtual device</option>
	</select>
<input type="submit" name="zmien_typ" value="zmień typ">
</form>
<? 
} else
	echo("<b>$device_type</b>"); 

