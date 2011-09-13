<?php

$menu_rows = null;
switch($device['device_type'])
{
  case "Host":
    require('device_menus/host.php');
    break;
  case "Virtual":
 //   require('device_menus/Virtual.php');
    break;
  case "Bramka_voip":
    require('device_menus/Bramka_voip.php');
    break;
  case "":
    break;
}
