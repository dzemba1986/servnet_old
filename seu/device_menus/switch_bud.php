<?php
require(SEU_ABSOLUTE.'/include/classes/ipV4.php');

function generateMenu($device)
{
	$daddy = new Daddy();
	$parent_id1 = $daddy->getDeviceModel($device['dev_id']);
	$parent_id = $parent_id1['id'];

	if($parent_id == '46' || $parent_id == '47'){
	
		$menu = "<h2>Operacje</h2><ul>";
		$menu.="<li><a target=\"_blank\" href=\"dev/x210/all_hosts.php?device=".$device['dev_id']."\">Add all Hosts</a></li>";
		$menu.="<li><a target=\"_blank\" href=\"dev/x210/drop_all_hosts.php?device=".$device['dev_id']."\">Drop all Hosts</a></li>";
		$menu.="</ul>";
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
