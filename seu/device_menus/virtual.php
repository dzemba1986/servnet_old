<?php
function generateMenu($device, $menu_rows)
{
  $menu = "<h2>Operacje</h2><ul>";
  $dev_id = $device['dev_id'];
  $menu.="<li><a href=\"usun.php?dev_id=$dev_id\">Usuń virtual</a></li>";
  $menu.="</ul>";
  echo $menu;
}
?>
<?php
generateMenu($device, $menu_rows);
