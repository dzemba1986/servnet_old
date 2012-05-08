<?php
require('../security.php');
require('../include/definitions.php');
require('../include/classes/reaport.php');
$date = date("Y.m.d");
$repo = new Reaport();
$ips = $repo->getSwitch_IPS($_GET['switch_type']);
if($ips)
{
  $type='';
  if($_GET['switch_type']=='bud')
    $type='Switch_bud';
  else
    $type='Switch_rejon';
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"$date.".$type."_ips.csv\""); 
  echo "ip;Podsiec;Maska;vlan;Osiedle;Blok;Klatka;Nazwa;Model\n";
  foreach($ips as $ip)
    echo $ip['ip'].";".$ip['Podsiec'].";".$ip['Maska'].";".$ip['vlan'].";".$ip['osiedle'].";".$ip['nr_bloku'].";".$ip['klatka'].";".$ip['nazwa'].";".$ip['Model']."\n";
}
else
 die("Nic do pobrania");
