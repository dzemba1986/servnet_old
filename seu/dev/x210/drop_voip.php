<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Drop VoIP</title>
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
$porty = array('first' => '1', 'last' => '47');

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
interface <b>port1.0.<?php echo($port); ?></b><br>
shutdown<br>
no access-group voip<?php echo($port); ?><br>
switchport access vlan 555<br>
no shutdown<br>
exit<br>
no access-list hardware voip<?php echo($port); ?><br>
exit<br>
wr<br>
&nbsp;<br>
<?php
//*******************************************************************************
}
else
{
?>

<form action="" method="get">
<center>
<br><h3>Generator konfiguracji przelacznika dla usunięcia voip</h3><br>
<table>
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
