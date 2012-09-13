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
#			'4' =>
#traffic-shape 4096 409600\n<br>
#rate-limit 6500<br>",
#			'8' =>
#traffic-shape 8192 819200\n<br>
#rate-limit 6900<br>",
			'30' =>
"traffic-shape 30720 307200\n<br>
rate-limit 28500<br>",
#			'100' =>
#"traffic-shape 102400 1024000\n<br>
#rate-limit 100000<br>",
#			'200' =>
#"traffic-shape 204800 2048000\n<br>
#rate-limit 305000<br>", 
#			'250' =>
#"traffic-shape 250000 2500000 \n<br>
#rate-limit 305002<br>")
			'300' =>
"traffic-shape 307200 3072000 \n<br>
rate-limit 305004<br>");

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
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
<?php 
echo "switchport access vlan $net_vlan<br>\n";
?>
description <b><?php echo($description); ?></b><br>
service-acl input <b>user<?php echo($port); ?></b><br>
<b><?php echo($predkosc_str[$predkosc]); ?></b>
port security mode lock<br>
port security discard<br>
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
<tr><td>mac</td><td><input type="text" name="mac" value="<? echo ($mac) ?>"/></td></tr>
<tr><td>ip</td><td><input type="text" name="ip" value="<? echo ($ip) ?>"/></td></tr>
<tr><td>description</td><td><input type="text" name="description" value="<? echo ($address) ?>"/></td></tr>
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
<tr><td>predkosc</td><td><select name="predkosc">
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
