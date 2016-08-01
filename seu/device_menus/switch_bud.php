<?php
require(SEU_ABSOLUTE.'/include/classes/ipV4.php');

function generateMenu($device)
{
	$daddy = new Daddy();
    
    //tabela $device posiada pola: 
    //dev_id, exists, virtual, mac,	gateway, lokalizacja, opis,	device_type, other_name,
    //device, sn, model, producent,	typ, port_count, id, osiedle, nr_bloku,	klatka,	ulic
    //device, local_port, parent_device, parent_port, uplink

	//$id_model = $daddy->getDeviceModel($device['dev_id']);
	//$parent_id = $parent_id1['id'];
    
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
        
        $ip_parent = $daddy->getIpAddresses($device['parent_device']);
        //var_dump($ip_parent[0][3]);

	if($device['model'] == '46' || $device['model'] == '47' || $device['model'] == '60' || $device['model'] == '59'){ //czy urządzenie jest z serii x
        
        if(substr($ip_parent[0][3], 0, 6) == '172.20'){  //czy rodzic - urządzenie jest z Winograd

            $menu = "<h2>Operacje</h2><ul>";
            $menu.="<li><a target=\"_blank\" href=\"dev/x210/winogrady/all_hosts.php?device=".$device['dev_id']."\">Add all Hosts</a></li>";
            $menu.="<li><a target=\"_blank\" href=\"dev/x210/winogrady/drop_all_hosts.php?device=".$device['dev_id']."\">Drop all Hosts</a></li>";
            $menu.="</ul>";
        }
        else {

            $menu = "<h2>Operacje</h2><ul>";
            $menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/all_hosts.php?device=".$device['dev_id']."\">Add all Hosts</a></li>";
            $menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/drop_all_hosts.php?device=".$device['dev_id']."\">Drop all Hosts</a></li>";
            $menu.="</ul>";
        }
	}
	else{
		
		$menu = "<h2>Operacje</h2><ul>";
		$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/all_hosts.php?device=".$device['dev_id']."\">Add all Hosts</a></li>";
		$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/drop_all_hosts.php?device=".$device['dev_id']."\">Drop all Hosts</a></li>";
		$menu.="</ul>";
		
	}

  echo $menu;
}

generateMenu($device);
