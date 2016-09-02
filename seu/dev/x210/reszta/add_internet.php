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
$mac1 = $_REQUEST['mac'];
$mac2 = str_replace(':', '', $mac1);
$mac = join('.', str_split($mac2, 4)); //zmiana formaru dla x210

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
"egress-rate-limit 508032k \n<br>
service-policy input internet-user-500M<br>");

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
interface <b>port1.0.<?php echo($port); ?></b><br>
shutdown<br>
no switchport port-security<br>
switchport port-security violation protect<br>
switchport port-security maximum 0<br>
switchport port-security<br>
description <b><?php echo($description); ?></b><br>
<b><?php echo($predkosc_str['500']); // była $predkosc ?></b>
access-group internet-user<br>
switchport access vlan <b><?php echo($net_vlan); ?></b><br>
spanning-tree portfast<br>
spanning-tree portfast bpdu-guard enable<br>
no shutdown<br>
exit<br>
mac address-table static <b><?php echo($_REQUEST['mac']); ?></b> forward interface <b>port1.0.<?php echo($port); ?></b> vlan <b><?php echo($net_vlan); ?></b><br>
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
<br><h3>Generator konfiguracji przelacznika dla internetu</h3><br>
<table>
<tr><td>mac</td><td><input type="text" name="mac" value="<?php echo ($mac) ?>"/></td></tr>
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
<tr><td>predkość</td><td><select name="predkosc">
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
