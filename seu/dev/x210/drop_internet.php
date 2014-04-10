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
#			'4' =>
#traffic-shape 4096 409600\n<br>
#rate-limit 6500<br>",
#			'8' =>
#traffic-shape 8192 819200\n<br>
#rate-limit 6900<br>",
		'30' =>
		"no service-policy input 30Mbps<br>",
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
		"no service-policy input 300Mbps<br>");


//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
no mac address-table static <b><?php echo($_REQUEST['mac']); ?></b> forward interface <b>port1.0.<?php echo($port); ?></b> vlan <b><?php echo($net_vlan); ?></b><br>
interface <b>port1.0.<?php echo($port); ?></b><br>
no switchport port-security<br>
<b><?php echo($predkosc_str[$predkosc]); ?></b>
no egress-rate-limit<br>
no access-group anyuser<br>
switchport access vlan 555<br>
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
<tr><td>mac</td><td><input type="text" name="mac" value="<? echo ($mac) ?>"/></td></tr>
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
