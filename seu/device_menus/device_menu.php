<?php

$menu_rows = null;
if(!defined('MAGAZYN'))
{?>

<h2>Nawigacja</h2>
<ul>
  <li><a href="index.php?device=<?php echo $device['dev_id'];?>">Wróć</a></li>
  <li><a href="modyfikuj.php?device=<?php echo $device['dev_id'];?>">Odśwież</a></li>
</ul>
<?php
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
}
