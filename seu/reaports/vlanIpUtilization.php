<?php
require('../security.php');
require('../include/definitions.php');
require('../include/reaport.php');
$vid = intval($_GET['vid']);
if(!$vid)
  $vid=2;
$sort = htmlspecialchars($_GET['sort']);
$repo = new Reaport();
$rows = $repo->getVlanIpUtilization($vid, $sort);
$daddy = new Daddy();
$vlans = $daddy->getVlansArray();
//print_r($switchL2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Wykorzystanie Adresów IP vlanu <?php echo $vid; ?></title>

  <link rel="stylesheet" href="../css/reaport.css" type="text/css" />
</head>
<body>
<div class="switch_rej" style="width: 510px; margin-top: 100px;">
<form action="vlanIpUtilization.php" method="get">
<center>Wykorzystanie Adresów IP vlanu  <select name="vid">
<?php
foreach($vlans as $vlan)
  if($vid==$vlan['vid'])
    echo "<option value=\"".$vlan['vid']."\" selected>".$vlan['vid']." (".$vlan['opis'].")</option>";
  else
    echo "<option value=\"".$vlan['vid']."\">".$vlan['vid']." (".$vlan['opis'].")</option>";
?>
</select> <input type="submit" value="Wybierz">
</form></center>
  <table border="1" style="width: 500px;">
  <tr class="title">
    <td width="150"><a href="vlanIpUtilization.php?sort=name&vid=<?php echo $vid; ?>">Nazwa Podsieci</a></td>
    <td width="150"><a href="vlanIpUtilization.php?sort=ip&vid=<?php echo $vid; ?>">IP Podsieci</a></td>
    <td width="100"><a href="vlanIpUtilization.php?sort=size&vid=<?php echo $vid; ?>">Rozmiar</a></td>
    <td width="100"><a href="vlanIpUtilization.php?sort=free&vid=<?php echo $vid; ?>">Wolnych adresów</a></td>
<?php

foreach ($rows as $row)
  {
    echo "
      <tr><td>".$row['name']."</td>
      <td>".$row['ip']."/".$row['netmask']."</td>
      <td>".$row['size']."</td>
      <td>".$row['free']."</td></tr>";
  }
  ?>
  </table>
</div>
<br>
<?php
