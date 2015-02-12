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
$mac2 = $_REQUEST['mac2'];
$port = $_REQUEST['port']; //3 - 22
$net_vlan = $_REQUEST['net_vlan'];
$net_vlany = array('2', '4');
$porty = array('first' => '1', 'last' => '47');

//*******************************************************************
if($_REQUEST['wygeneruj'])
{
?>
interface ethernet <b>g<?php echo($port); ?></b><br>
shutdown<br>
no port security<br>
exit<br>
interface vlan <?php echo($net_vlan); ?><br>
no bridge address <b><?php echo($_REQUEST['mac']); ?></b><br>
bridge address <b><?php echo($_REQUEST['mac2']); ?></b> permanent ethernet <b>g<?php echo($port); ?></b><br>
exit<br>
interface ethernet <b>g<?php echo($port); ?></b><br>
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
<br><h3>Generator zmiany adresu mac abonenta</h3><br>
<table>
<tr><td>Stary mac</td><td><input type="text" name="mac" value="<?php echo ($mac) ?>"/></td></tr>
<tr><td>Nowy mac</td><td><input type="text" name="mac2" value="<?php echo ($mac2) ?>"/></td></tr>
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
