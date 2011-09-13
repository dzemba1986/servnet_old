<?php
function generateMenu($device)
{
  $menu = "<h2>Operacje</h2><ul>";
  $daddy = new Daddy();
  $mac = $device['mac'];
  $speed = $device['pakiet']; 
  $loc = $device['osiedle'].$device['nr_bloku']."/".$device['nr_mieszkania']; 
  $ips = $daddy->getIpAddresses($device['dev_id']);
  $ip = ''; 
  foreach($ips as $_ip)
  {
    if($_ip[2]=1)
    {
      $ip = $_ip[3];
      break;
    }
  }
  $port = '';
  $uplinks = $daddy->getUplinkConnections($device['dev_id']);
  $port = substr($uplinks[0]['parent_port'], 1);
  $menu.="<li><a target=\"_blank\" href=\"dev/8000GS/add_internet.php?mac=$mac&port=$port&address=$loc&ip=$ip&speed=$speed\">8000GS user_add</a></li>";
  $menu.="<li><a target=\"_blank\" href=\"dev/8000GS/drop_internet.php?mac=$mac&port=$port\">8000GS user_drop</a></li>";
  $menu.="<li><a target=\"_blank\" href=\"dev/8000GS/change_mac.php?mac=$mac&port=$port\">8000GS change mac</a></li>";
  $menu.="</ul>";
  echo $menu;
}
?>
<h2>Nawigacja</h2>
<ul>
  <li><a href="index.php?device=<?php echo $device['dev_id'];?>">Wróć</a></li>
  <li><a href="modyfikuj.php?device=<?php echo $device['dev_id'];?>">Odśwież</a></li>
</ul>
<?php
generateMenu($device);
