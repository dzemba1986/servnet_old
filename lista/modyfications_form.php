<?php require('include/html/header.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/mysql.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/localization.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/connections.php'); ?>
<?php
$mod_id = $_GET['mod_id'];
$con_id = $_GET['con_id'];
$mod = null;
$loc_arr = null;

if($con_id)
{
  $mod_id = Connections::getModId($con_id);
  $loc_id = Connections::getLocId($con_id);
  $loc = new Lokalizacja();
  $loc_arr = $loc->getLoc($loc_id);
  $loc_arr['str'] = $loc->getAddressStr($loc_id);
}

if($mod_id)
{
  echo $mod_id;
  $mod = Modyfications::getById($mod_id);
  if(!$con_id)
  {
    $loc_id = $mod->get_loc();
    $loc = new Lokalizacja();
    $loc_arr = $loc->getLoc($loc_id);
    $loc_arr['str'] = $loc->getAddressStr($loc_id);
  }
}
else
  $mod = new Modyfications();
var_dump($mod);
$sql = new myMysql();
$streets = $sql->getUlic();
?>
<div>
  <form action="modyfications.php?tryb=active_modyfications" method="post">
  <table class="tables" style="margin: 50px 0px 0px 50px">
  <tr>
    <td>Początek</td>
    <td width="185"><div style="float: left; padding-top:5px">D:</div><input class="date_field" type="text" value="<?php echo($mod->get_s_date())?>" name="s_date" id="s_date"> T:<input class="time_field" type="text" value="<?php echo($mod->get_s_time())?>" name="s_time" id="s_time"></td>
  </tr>
  <tr>
    <td>Koniec</td>
    <td width="185"><div style="float: left; padding-top:5px">D:</div><input class="date_field" type="text" value="<?php echo($mod->get_e_date())?>" name="e_date" id="e_date"> T:<input class="time_field" type="text" value="<?php echo($mod->get_e_time())?>" name="e_time" id="e_time"></td>
  </tr>
  <tr>
    <td>Cena</td>
    <td><input type="text" name="cost" style="width:50px" value="<?php echo($mod->get_cost())?>"/> zł</td>
  </tr>
  <tr>
    <td>Typ instalacji</td>
    <td>
    <select name="inst">
      <option>net</option>
      <option>tv</option>
      <option>phone</option>
      <option>other</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Rodzaj przeróbki</td>
    <td>
    <select name="type">
      <option>inst_new</option>
      <option>inst_change</option>
      <option>socket_add</option>
      <option>socket_change</option>
      <option>wire_change</option>
      <option>modyfication</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Przyczyna przeróbki</td>
    <td>
    <select name="cause">
      <option>devastation_out</option>
      <option>devastation_in</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>Lokalizacja</td>
    <td>
    <?php if($loc_arr):
    echo($loc_arr['str']);
    ?>
    <input type="hidden" name="street" value="<?php echo($loc_arr['id']); ?>" />
    <?php else: ?>
    <select name="street">
      <option></option>
    <?php foreach ($streets as $street)
      echo '<option value="'.$street['ULIC'].'">'.$street['short_name']."</option>\n";
    ?>
    </select><br />
    <input type="text" name="building" style="width:50px" value="<?php echo($mod->get_building())?>" /> /<input type="text" name="flat" style="width:50px" value="<?php echo($mod->get_flat())?>" />
    Niepowiązany z Abonentem
    <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>Info</td>
    <td><textarea name="desc"><?php echo($mod->get_desc())?></textarea></td>
  </tr>
  <tr>
    <td><button onclick="closeMod()">Zamknij</button>
    </td>
    <td><input type="submit" value="Zapisz" />
    </td>
  </tr>
  </table>
    <input type="hidden" name="id" value="<?php echo($mod->get_id()); ?>"/>
  </form>
</table>
</div>
</body>
</html>
