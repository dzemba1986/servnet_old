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
$mac_a = $_REQUEST['mac'];
$mac_b = str_replace(':', '', $mac_a);
$mac = join('.', str_split($mac_b, 4)); //zmiana formaru dla x210

$mac2_a = $_REQUEST['mac2'];
$mac2_b = str_replace(':', '', $mac2_a);
$mac2 = join('.', str_split($mac2_b, 4)); //zmiana formaru dla x210

//$mac2 = $_REQUEST['mac2'];

$ip = $_REQUEST['ip'];
$port = $_REQUEST['port']; //3 - 22
$net_vlan = $_REQUEST['net_vlan'];
$net_vlany = array('2', '4');
$porty = array('first' => '1', 'last' => '47');

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
interface <b>port1.0.<?php echo($port); ?></b><br>
shutdown<br>
exit<br>
no mac address-table static <b><?php echo($_REQUEST['mac']); ?></b> forward interface <b>port1.0.<?php echo($port); ?></b> vlan <b><?php echo($net_vlan); ?></b><br>
mac address-table static <b><?php echo($_REQUEST['mac2']); ?></b> forward interface <b>port1.0.<?php echo($port); ?></b> vlan <b><?php echo($net_vlan); ?></b><br>
clear ip dhcp snooping binding <b><?php echo($ip);?></b></br>
interface ethernet <b>port1.0.<?php echo($port); ?></b><br>
no shutdown<br>
exit<br>
exit<br>
wr<br>
<?php
//*******************************************************************************
}
else
{
?>

<form action="" method="get">
<center>
<br><h3>Generator zmiany adresu mac abonenta</h3><br>
<table>
<tr><td>Stary mac</td><td><input type="text" name="mac" value="<? echo ($mac) ?>"/></td></tr>
<tr><td>Nowy mac</td><td><input type="text" name="mac2" value="<? echo ($mac2) ?>"/></td></tr>
<tr><td><input type="hidden" name="ip" value="<? echo ($ip) ?>"/></td></tr>
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
<tr><td></td><td><input type="submit" name="wygeneruj" value="Wygeneruj kod" /></td></tr>
</table>
</center>
</form>



<?php
}
?>
</body>
</html>
