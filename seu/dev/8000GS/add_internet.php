<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Add internet</title>
</head>
<body>
<?php
//*******************************************************************
// zmienne
//*******************************************************************
$mac = $_REQUEST['mac'];
$port = $_REQUEST['port']; //3 - 22
$ip = $_REQUEST['ip'];
$description = $_REQUEST['description'];
$predkosc = $_REQUEST['predkosc'];
$speed = $_REQUEST['speed'];
$address = $_REQUEST['address'];
$net_vlan = $_REQUEST['net_vlan'];

$net_vlany = array('2', '4');
$vlany = array('20', '22', '24', '26', '28');
$porty = array('first' => '1', 'last' => '47');
//*******************************************************************
//opcje predkosci
//*******************************************************************

$predkosc_str = array( 
			'500' =>
"traffic-shape 520000 5200000 \n<br>
rate-limit 800000<br>");

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
interface ethernet <b>g<?php echo($port); ?></b><br>
no service-acl input<br>
exit<br>
no ip access-list <b>user<?php echo($port); ?></b><br>
no ip access-list <b>user<?php echo($port); ?></b><br>
interface vlan <?php echo($net_vlan); ?><br>
bridge address <b><?php echo($_REQUEST['mac']); ?></b> permanent ethernet <b>g<?php echo($port); ?></b><br>
exit<br>
ip access-list <b>user<?php echo($port); ?></b><br>
deny-udp any any any 68<br>
deny-tcp any any any 25<br>
permit any <b><?php echo($ip); ?></b> 0.0.0.0 any<br>
permit-udp 0.0.0.0 0.0.0.0 68 any 67<br>
exit<br>
interface ethernet <b>g<?php echo($port); ?></b><br>
shutdown<br>
switchport trunk allowed vlan remove all<br>
switchport mode access<br>
<?php 
echo "switchport access vlan $net_vlan<br>\n";
?>
description <b><?php echo($description); ?></b><br>
service-acl input <b>user<?php echo($port); ?></b><br>
<b><?php echo($predkosc_str['500']); // była $predkosc ?></b>
port security mode lock<br>
port security discard<br>
spanning-tree portfast<br>
spanning-tree bpduguard<br>
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
<br><h3>Generator konfiguracji przelacznika dla internetu</h3><br>
<table>
<tr><td>mac</td><td><input type="text" name="mac" value="<?php echo ($mac) ?>"/></td></tr>
<tr><td>ip</td><td><input type="text" name="ip" value="<?php echo ($ip) ?>"/></td></tr>
<tr><td>description</td><td><input type="text" name="description" value="<?php echo ($address) ?>"/></td></tr>
<tr>
  <td>vlan</td>
  <td>
    <select name="net_vlan">
      <?php foreach($net_vlany as $form_vlan) 
      if($form_vlan==$net_vlan)
        echo"<option selected>$form_vlan</option>"; 
      else
        echo"<option>$form_vlan</option>"; 
      ?>
    </select>
  </td>
</tr>
<tr><td>port</td><td><select name="port">
<?php for($i=$porty['first']; $i<=$porty['last']; $i++)
{
  if($port==$i)
    echo"<option value=\"$i\" selected>$i</option>"; 
  else
    echo"<option value=\"$i\">$i</option>"; 
}
?></select></td></tr>
<tr><td>prędkość</td><td><select name="predkosc">
<?php foreach($predkosc_str as $key=>$wartosc)
{
  if($speed==$key)
    echo"<option value=\"$key\" selected>$key/".$key/2.0." Mbps</option>"; 
  else
    echo"<option value=\"$key\">$key/".$key/2.0." Mbps</option>"; 
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
