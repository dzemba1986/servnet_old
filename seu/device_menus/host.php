<?php
require(SEU_ABSOLUTE.'/include/classes/ipV4.php');
function generateMenu($device)
{
  $menu = "<h2>Operacje</h2><ul>";
  $daddy = new Daddy();
  
  //tabela $device posiada pola: 
  //dev_id	exists	virtual	mac	gateway	lokalizacja	opis	device_type	other_name	id	osiedle	nr_bloku	klatka	ulic
  //device	local_port	parent_device	parent_port	uplink	device	nr_mieszkania	pakiet	con_id	data_uruchomienia	
  //data_zakonczenia	start	stop

  $mac = $device['mac'];
  $speed = $device['pakiet']; 
  $loc = $device['osiedle'].$device['nr_bloku']."/".$device['nr_mieszkania'];
  
  //tabela $ips to tablica 2 wymiarowa:
  //Array ( [0] => Array ( [0] => 4 [1] => 5.133.249.154/25 [2] => 1 [3] => 5.133.249.154 ) ) 
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
  
  //tabela $uplinks posiada pola:
  //device, local_port, parent_device, parent_port, uplink
  $uplinks = $daddy->getUplinkConnections($device['dev_id']);
  
  //pobieram model rodzica
  $_parent_id_model = $daddy->getDeviceModel($device['parent_device']);
  $parent_id_model = $_parent_id_model['id'];
  
  $parent_ip = $daddy->getIpAddresses($device['parent_device']);
  $ip_parent = ''; 
  foreach($parent_ip as $_ip)
  {
    if($_ip[2]=1)
    {
      $ip_parent = $_ip[3];
      break;
    }
  }
  
  
  if($parent_id_model == '46' || $parent_id_model == '47')
  	$port = substr($uplinks[0]['parent_port'], 8);
  else
  	$port = substr($uplinks[0]['parent_port'], 1);
  
  if($parent_id_model == '46' || $parent_id_model == '47' || $parent_id_model == '60'){ //czy rodzic - urządzenie jest z serii x
  	
    if(substr($ip_parent, 0, 6) == '172.20'){  //czy rodzic - urządzenie jest z Winograd
        //var_dump(substr($ip_parent, 0, 6));
        
        $menu.="<li><a target=\"_blank\" href=\"dev/x210/winogrady/add_internet.php?mac=$mac&port=$port&address=$loc&speed=$speed&net_vlan=$vlan\">x210 user_add</a></li>";
        $menu.="<li><a target=\"_blank\" href=\"dev/x210/winogrady/drop_internet.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 user_drop</a></li>";
        //$menu.="<li><a target=\"_blank\" href=\"dev/x210/winogrady/change_mac.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 change mac</a></li>";
        $menu.="</ul>";
    }
    else {
        //var_dump($ip_parent);
        $menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/add_internet.php?mac=$mac&port=$port&address=$loc&speed=$speed&net_vlan=$vlan\">x210 user_add_net</a></li>";
        $menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/add_internet_iptv.php?mac=$mac&port=$port&address=$loc&speed=$speed&net_vlan=$vlan\">x210 user_add_iptv</a></li>";
        $menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/drop_service.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 user_drop</a></li>";
        //$menu.="<li><a target=\"_blank\" href=\"dev/x210/reszta/change_mac.php?mac=$mac&net_vlan=$vlan&port=$port\">x210 change mac</a></li>";
        $menu.="</ul>";
    }
  }
  else{	
  
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/add_internet.php?mac=$mac&port=$port&address=$loc&ip=$ip&speed=$speed&net_vlan=$vlan\">8000GS user_add</a></li>";
  	$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/drop_internet.php?mac=$mac&net_vlan=$vlan&port=$port\">8000GS user_drop</a></li>";
  	//$menu.="<li><a target=\"_blank\" href=\"dev/8000GS/change_mac.php?mac=$mac&net_vlan=$vlan&port=$port\">8000GS change mac</a></li>";
  	$menu.="</ul>";
  }
  echo $menu;
}
?>
<?php
generateMenu($device);

//var_dump($daddy->getDeviceModel($device['parent_device'])['id']);
