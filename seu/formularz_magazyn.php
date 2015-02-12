<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">

<title>Magazyn</title>
  <link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/dodaj.css" type="text/css" />
  <link rel="stylesheet" href="css/black/menu.css" type="text/css" >
  <link rel="stylesheet" href="css/black/naglowek.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
<script language="JavaScript" SRC="js/xml.js"></script>
<script language="JavaScript" SRC="js/producentForm.js"></script>
<script language="JavaScript" SRC="js/modelForm.js"></script>
	
</head>
<body>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
<div id="formularz">
<b>Magazyn</b>
<br><br>
<?php echo $komunikat ?>
<form method="post">
<table>
<tr>
	<td>Typ urządzenia</td>
	<td><select class="" name="device_type" id="device_type" value="">
		<option></option>
		<option>Switch_bud</option>
		<option>Bramka_voip</option>
		<option>Kamera</option>
		<option>Serwer</option>
		<option>Router</option>
		<option>Switch_rejon</option>
	</td>
</tr>
<tr>
	<td>Producent *</td>
	<td><select class="" name="producent" id="producent" value=""></td>
</tr>
<tr>
	<td>Model *</td>
	<td><select class="" name="model" id="model" value=""></td>
</tr>
			
<tr><td>mac</td><td><input type="text" name="mac"></td><td>&nbsp</td></tr>
<tr><td>sn</td><td><input type="text" name="sn"></td></select><td><input type="hidden" name="timestamp" value="<?php echo(time());?>"><input type="submit" name="dodaj_do_magazynu" value="dodaj"></td></tr>
</table>
<?php require("formularz_skrypt_producent.php");?>
</form>
</div>
<div style="clear: both;"></div>
<div>

<table border="1" class="opis">
<tr style="background:#888;">
<td>dev_id</td><td>typ</td><td>producent</td><td>model</td><td>sn</td><td>mac</td><td>nazwa</td><td></td>
</tr>
<?php
$devices = $daddy->getMagazynEntries(); 
foreach($devices as $device)
echo"
	<tr>
	<td>".$device['dev_id']."</td>
	<td>".$device['device_type']."</td>
	<td>".$device['producent']."</td>
	<td>".$device['model']."</td>
	<td>".$device['sn']."</td>
	<td>".$device['mac']."</td>
	<td>".$device['other_name']."</td>
	<td><a href=\"modyfikuj_magazyn.php?device=".$device['dev_id']."\">Modyfikuj</a> <a href=\"modyfikuj.php?device=".$device['dev_id']."&magazyn=true\">Zamontuj</a></td>
	</tr>
"
?>
</table>
</div>
</div>
<div id="dol"></div>
</div>
</body>
</html>

