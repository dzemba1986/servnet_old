<?php
require('../security.php');
require('../include/definitions.php');
require('../include/classes/reaport.php');
$date = date("Y.m.d");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$date.8000GS_ips.csv\""); 
$repo = new Reaport();
$ips = $repo->get8000GS_IPS();
echo "ip;lokalizacja\n";
foreach($ips as $ip)
  echo $ip['ip'].";".$ip['lok']."\n";
