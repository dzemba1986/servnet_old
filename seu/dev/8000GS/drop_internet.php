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
$mac = $_REQUEST['mac'];
$port = $_REQUEST['port']; //3 - 22
$net_vlan = $_REQUEST['net_vlan'];

$net_vlany = array('2', '4');
$vlany = array('20', '22', '24', '26', '28');
$porty = array('first' => '1', 'last' => '47');

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>


interface vlan <?php echo($net_vlan); ?><br>
! Podac MACa klienta i port<br>
no bridge address <b><?php echo($_REQUEST['mac']); ?></b><br>
exit<br>
! Podac port klienta<br>
interface ethernet g<?php echo($port); ?><br>
shutdown<br>
! Podac nazwe ACLki dla klienta<br>
no service-acl input<br>
no traffic-shape<br>
no rate-limit<br>
no port security<br>
sw a v 555<br>
no shutdown<br>
exit<br>
! Podac nazwe ACLki: user3, user4 itd (nr taki jak port)<br>
no ip access-list user<?php echo($port); ?><br>
no ip access-list user<?php echo($port); ?><br>
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
<tr><td></td><td><input type="submit" name="wygeneruj" value="Wygeneruj kod" /></td></tr>
</table>
</center>
</form>



<?php
}
?>
</body>
</html>
