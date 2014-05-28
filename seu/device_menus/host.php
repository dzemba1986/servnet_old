<?php
require(SEU_ABSOLUTE.'/include/classes/ipV4.php');
function generateMenu($device)
{
  $menu = "<h2>Operacje</h2><ul>";
  $daddy = new Daddy();
  $mac = $device['mac'];
  $speed = $device['pakiet']; 
  $loc = $device['osiedle'].$device['nr_bloku']."/".$device['nr_mieszkania']; 
  $ips = $daddy->getIpAddresses($device['dev_id']);
  $ip = ''; 
  $vlan = IpV4Address::getIpVlan($device['dev_id']);
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
  
  $parent_id1 = $daddy->getDeviceModel($device['parent_device']);
  $parent_id = $parent_id1['id'];
  
  if($parent_id == '46' || $parent_id == '47')
  	$port = substr($uplinks[0]['parent_port'], 8);
  else
  	$port = substr($uplinks[0]['parent_port'], 1);
  
  if($parent_id == '46' || $parent_id == '47'){
  	
  	$menu.="<li><a target=\"_blank\" href=\"dev/x210/add_internet.php?mac=$mac&port=$port&address=$loc&speed=$speed&net_vlan=$vlan\">x210 user_add</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/x210/drop_internet.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 user_drop</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/x210/change_mac.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 change mac</a></li>";
  	$menu.="</ul>";
  	
  }
  else{	
  
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/add_internet.php?mac=$mac&port=$port&address=$loc&ip=$ip&speed=$speed&net_vlan=$vlan\">8000GS user_add</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/drop_internet.php?mac=$mac&net_vlan=$vlan&port=$port\">8000GS user_drop</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/change_mac.php?mac=$mac&net_vlan=$vlan&port=$port\">8000GS change mac</a></li>";
  	$menu.="</ul>";
  }
  echo $menu;
}
?>
<?php
generateMenu($device);

//var_dump($daddy->getDeviceModel($device['parent_device'])['id']);
