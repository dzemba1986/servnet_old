<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>drop internet</title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
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
$net_vlan = $_REQUEST['net_vlan'];
$predkosc = $_REQUEST['predkosc'];
$speed = $_REQUEST['speed'];

$net_vlany = array('2', '4');
$vlany = array('20', '22', '24', '26', '28');
$porty = array('first' => '1', 'last' => '47');

$predkosc_str = array(
		'300' =>
		"no service-policy input iptv-user-300M<br>");


//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
no mac address-table static <b><?php echo($_REQUEST['mac']); ?></b> forward interface <b>port1.0.<?php echo($port); ?></b> vlan <b><?php echo($net_vlan); ?></b><br>
interface <b>port1.0.<?php echo($port); ?></b><br>
no switchport port-security<br>
<b><?php echo($predkosc_str[$predkosc]); ?></b>
no egress-rate-limit<br>
no access-group iptv-user<br>
switchport access vlan 555<br>
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
<br><h3>Generator konfiguracji przelacznika dla usunięcia abonenta internetowego</h3><br>
<table>
<tr><td>mac</td><td><input type="text" name="mac" value="<?php echo ($mac) ?>"/></td></tr>
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
