<?php
require('../security.php');
require('../include/definitions.php');
require('../include/reaport.php');
$repo = new Reaport();
$switchL2 = $repo->getL2Switches();
$switchL3 = $repo->getL3Switches();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Struktura sieci</title>

  <link rel="stylesheet" href="../css/reaport.css" type="text/css" />
</head>
<body>
<?php

foreach ($switchL3 as $switch)
{
  $ports = $repo->getSwitchPorts($switch['dev_id']);
  $ips = $repo->getDevIps($switch['dev_id']);
?>
<div class="switch">
  <div class="sw_desc">
    <table>
    <tr>
      <td class="title">Lokalizacja</td>
      <td class="location"><?php echo ($switch['osiedle'].$switch['nr_bloku'].$switch['klatka']);?></td>
    </tr>
    <tr>
      <td class="title">Nazwa</td>
      <td><?php echo ($switch['other_name']);?></td>
    </tr>
    <tr>
      <td class="title">MAC</td>
      <td><?php echo ($switch['mac']);?></td>
    </tr>
    <tr>
      <td class="title">Model</td>
      <td><?php echo ($switch['name']);?></td>
    </tr>
    <tr>
      <td class="title">SN</td>
      <td><?php echo ($switch['sn']);?></td>
    </tr>
    </table>
  </div>
  <div class="sw_ip">
  <table>
  <?php
  echo "<tr class=\"title\"><td>Adres</td><td>podsiec</td><td>maska</td><td>vlan</td></tr>";
  foreach($ips as $ip)
  {
    echo "<tr>";
    echo "<td>".$ip['ip']."</td><td>".$ip['subnet']."<td>".$ip['netmask']."</td><td>".$ip['vlan']."</td>";
    echo "</tr>";
  }
  ?>
  </table>
  </div>
  <div class="ports">
  <table border="1">
  <?php
  echo "<tr class=\"title\"><td>port</td><td>osiedle</td><td>blok</td><td>klatka</td><td>Nazwa</td><td>ip</td><td>Typ</td><td>MAC</td><td>sn</td><td>model</td></tr>";
  foreach($ports as $port)
  {
    echo "<tr><td>".$port['parent_port']."</td><td>".$port['osiedle']."</td><td>".$port['blok']."</td><td>".$port['klatka']."</td><td>".$port['other_name']."</td>
      <td>".$port['ip']."</td><td>".$port['device_type']."</td><td>".$port['mac']."</td><td>".$port['sn']."</td><td>".$port['model']."</td></tr>";
  }
  ?>
  </table>
  </div>
</div>
<br>
<?php
}
foreach ($switchL2 as $switch)
{
  $ports = $repo->getSwitchPorts($switch['dev_id']);
  $ips = $repo->getDevIps($switch['dev_id']);
?>
<div class="switch">
  <div class="sw_desc">
    <table>
    <tr>
      <td class="title">Lokalizacja</td>
      <td class="location"><?php echo ($switch['osiedle'].$switch['nr_bloku'].$switch['klatka']);?></td>
    </tr>
    <tr>
      <td class="title">Nazwa</td>
      <td><?php echo ($switch['other_name']);?></td>
    </tr>
    <tr>
      <td class="title">MAC</td>
      <td><?php echo ($switch['mac']);?></td>
    </tr>
    <tr>
      <td class="title">Model</td>
      <td><?php echo ($switch['name']);?></td>
    </tr>
    <tr>
      <td class="title">SN</td>
      <td><?php echo ($switch['sn']);?></td>
    </tr>
    </table>
  </div>
  <div class="sw_ip">
  <table>
  <?php
  echo "<tr class=\"title\"><td>Adres</td><td>podsiec</td><td>maska</td><td>vlan</td></tr>";
  foreach($ips as $ip)
  {
    echo "<tr>";
    echo "<td>".$ip['ip']."</td><td>".$ip['subnet']."<td>".$ip['netmask']."</td><td>".$ip['vlan']."</td>";
    echo "</tr>";
  }
  ?>
  </table>
  </div>
  <div class="ports">
  <table border="1">
  <?php
  echo "<tr class=\"title\"><td>port</td><td>osiedle</td><td>blok</td><td>klatka</td><td>ip</td><td>Typ</td><td>MAC</td><td>mieszkanie</td><td>pakiet</td><td>sn</td><td>model</td></tr>";
  foreach($ports as $port)
  {
    echo "<tr><td>".$port['parent_port']."</td><td>".$port['osiedle']."</td><td>".$port['blok']."</td><td>".$port['klatka']."</td>
      <td>".$port['ip']."</td><td>".$port['device_type']."</td><td>".$port['mac']."</td><td>".$port['nr_mieszkania']."</td>
      <td>".$port['pakiet']."</td><td>".$port['sn']."</td><td>".$port['model']."</td></tr>";
  }
  ?>
  </table>
  </div>
</div>
<br>
<?php
}
