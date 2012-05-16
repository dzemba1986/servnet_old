<?php
require(SEU_ABSOLUTE.'/include/classes/ipV4.php');
function generateMenu($device)
{
  $menu = "<h2>Operacje</h2><ul>";
  $menu.="<li><a target=\"_blank\" href=\"dev/8000GS/all_hosts.php?device=".$device['dev_id']."\">Add all Hosts</a></li>";
  $menu.="</ul>";
  echo $menu;
}
?>
<?php
generateMenu($device);
