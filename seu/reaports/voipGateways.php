<?php
require('../security.php');
require('../include/definitions.php');
require('../include/reaport.php');
$repo = new Reaport();
$voipGateways = $repo->getVoipGatewayReaport();
//$gatewayL2 = $repo->getL2SwitchReaport();
//print_r($gatewayL2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Bramki VoIP</title>

  <link rel="stylesheet" href="../css/reaport.css" type="text/css" />
</head>
<body>
<div class="switch_rej">
  <table border="1" width="1194">
  <tr class="title">
    <td width="20">osiedle</td>
    <td width="20">blok</td>
    <td width="50">klatka</td>
    <td width="150">v3_ip</td>
    <td width="120">MAC</td>
    <td width="100">sn</td>
    <td width="114">model</td>
    <td width="114">urz. nadrz</td>
    <td width="100">port</td></tr>
<?php

foreach ($voipGateways as $gateway)
  {
    echo "<tr><td>".$gateway['osiedle']."</td><td>".$gateway['blok']."</td><td>".$gateway['klatka']."</td><td>".$gateway['v3_ip']."</td>
      <td>".$gateway['mac']."</td><td>".$gateway['sn']."</td><td>".$gateway['name']."</td><td>".$gateway['parent_dev_lok']."</td><td>".$gateway['parent_port']."</td></tr>";
  }
  ?>
  </table>
</div>
<br>
<?php
