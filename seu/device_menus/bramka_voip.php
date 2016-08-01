<?php
function generateMenu($device, $menu_rows)
{
  $menu = "<h2>Operacje</h2><ul>";
  $daddy = new Daddy();
  $mac = $device['mac'];
  $speed = $device['pakiet']; 
  $loc = $device['osiedle'].$device['nr_bloku']; 
  $ips = $daddy->getIpAddresses($device['dev_id']);
  $ip = ''; 
  if($ips)
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
  
  if($parent_id == '46' || $parent_id == '47' || $parent_id == '60' || $parent_id == '59')
  	$port = substr($uplinks[0]['parent_port'], 8);
  else
  	$port = substr($uplinks[0]['parent_port'], 1);
  
  if($parent_id == '46' || $parent_id == '47' || $parent_id == '60' || $parent_id == '59'){
  	 
  	$menu.="<li><a target=\"_blank\" href=\"dev/x210/add_voip.php?port=$port&description=vo$loc&ip=$ip\">x210 voip_add</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/x210/drop_voip.php?port=$port\">x210 voip_drop</a></li>";
  	$menu.="</ul>";
  	 
  }
  else{
  
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/add_voip.php?port=$port&description=vo$loc&ip=$ip\">8000GS voip_add</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/drop_voip.php?port=$port\">8000GS voip_drop</a></li>";
  	$menu.="</ul>";
  }
  
  
  
  echo $menu;
}
?>
<?php
generateMenu($device, $menu_rows);
