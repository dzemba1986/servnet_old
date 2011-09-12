<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Add internet</title>
</head>
<body>
<?php
//*******************************************************************
// zmienne
//*******************************************************************
$port = $_REQUEST['port']; //3 - 22
$ip = $_REQUEST['ip'];
$description = $_REQUEST['description'];
$predkosc = $_REQUEST['predkosc'];
$porty = array('first' => '1', 'last' => '44');

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
ip access-list <b>user<?php echo($port); ?></b><br>
deny-udp any any any 68<br>
permit any <b><?php echo($ip); ?></b> 0.0.0.0 213.5.208.0 0.0.0.63<br>
permit any <b><?php echo($ip); ?></b> 0.0.0.0 213.5.208.128 0.0.0.63<br>
permit  any <b><?php echo($ip); ?></b> 0.0.0.0 10.111.0.0 0.0.255.255<br>
permit-udp 0.0.0.0 0.0.0.0 68 any 67<br>
exit<br>

interface ethernet <b>g<?php echo($port); ?></b><br>
shutdown<br>
! Podac lokalizację bramki<br>
description <b><?php echo($description); ?></b><br>
! Podac nazwe ACLki dla klienta<br>
switchport access vlan 3<br>
service-acl input <b>voip<?php echo($port); ?></b><br>
no shutdown<br>
exit<br>

exit<br>
copy r s<br>
y<br>
<?php
//*******************************************************************************
}
else
{
?>

<form action="" method="get">
<center>
<br><h3>Generator konfiguracji przelacznika dla voip</h3><br>
<table>
<tr><td>ip</td><td><input type="text" name="ip" value="<? echo ($ip) ?>"/></td></tr>
<tr><td>description</td><td><input type="text" name="description" value="<? echo ($address) ?>"/></td></tr>
<tr><td>port</td><td><select name="port">
<?php for($i=$porty['first']; $i<=$porty['last']; $i++)
{
  if($port==$i)
    echo"<option value=\"$i\" selected>$i</option>"; 
  else
    echo"<option value=\"$i\">$i</option>"; 
}
?></select></td></tr>
<tr><td></td><td><input type="submit" name="wygeneruj" value="Wygeneruj kod" /></td></tr>
</table>
</center>
</form>



<?php
}
?>
</body>
</html>
