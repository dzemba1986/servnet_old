<?php require('include/html/header.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/user.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/mysql.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/localization.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/connections.php'); ?>
<?php
$mod_id = intval($_GET['mod_id']);
$con_id = intval($_GET['con_id']);
$phone_id = intval($_GET['phone_id']);
$mod = null;
$loc_arr = null;
$inst_arr = array('net' => 'Internet',
                  'tv' => 'Telewizja',
                  'phone' => 'Telefon',
                  'net_phone' => 'Internet + telefon',
                  'other' => 'Inna');
$type_arr = array('inst_new' => 'Nowa instalacja',
                  'inst_change' => 'Wymiana instalacji',
                  'socket_add' => 'Nowe gniazdo',
                  'socket_change' => 'Wymiana gniazda',
                  'socket_move' => 'Przeniesienie gniazda',
                  'wire_change' => 'Wymiana przewodu',
                  'modyfication' => 'Inne przeróbki');
$cause_arr = array('devastation_in' => 'W lokalu',
                  'devastation_out' => 'Poza lokalem');
if($con_id)
{
  $mod_id = Connections::getModId($con_id);
  $loc_id = Connections::getLocId($con_id);
  $loc = new Lokalizacja();
  $loc_arr = $loc->getLoc($loc_id);
  $loc_arr['str'] = $loc->getAddressStr($loc_id);
  if(!$mod_id)
  {
    $mod = new Modyfications();
    $mod->set_desc(Connections::getInfo($con_id)."\n".Connections::getBoaInfo($con_id));
    $service1 = Connections::getService($con_id);
    $service2 = Connections::getService($phone_id);
    if($service1=='net' && $service2=='phone')
      $mod->set_inst('net_phone');
    else 
      $mod->set_inst(Connections::getService($con_id));
    $mod->set_cost(0);
    $mod->set_type('inst_new');
  }
}

if($mod_id)
{
  $mod = Modyfications::getById($mod_id);
  if(!$con_id)
  {
    $loc_id = $mod->get_loc();
    $loc = new Lokalizacja();
    $loc_arr = $loc->getLoc($loc_id);
    $loc_arr['str'] = $loc->getAddressStr($loc_id);
    if(!$_REQUEST['week_start_date'])
      $week_start_date = $mod->get_s_date();
  }
}
if(!$mod)
  $mod = new Modyfications();
$sql = new myMysql();
$streets = $sql->getUlic();

?>
<script type="text/javascript" src="js/edit.js"></script>
<script type="text/javascript" src="js/modyfications.js"></script>
<div>
  <form action="modyfications.php?tryb=modyfications" method="post">
  <table class="tables" style="margin: 50px 0px 0px 50px">
  <tr>
    <td>Dzień</td>
    <td width="185"><div style="float: left; padding-top:5px"></div>
      <input class="date_field" type="text" value="<?php echo($mod->get_s_date())?>" name="s_date" id="s_date" onkeyup="testModForm();">
      </td>
  </tr>
  <tr>
    <td>Godziny</td>
    <td width="185"><div style="float: left; padding-top:5px"></div>Od:<input class="time_field" type="text" value="<?php echo($mod->get_s_time())?>" name="s_time" id="s_time" onkeyup="testModForm();">
     Do:<input class="time_field" type="text" value="<?php echo($mod->get_e_time())?>" name="e_time" id="e_time" onkeyup="testModForm();"></td>
  </tr>
  <tr>
    <td>Koszt</td>
    <td><input type="text" name="cost" id="cost" style="width:50px" value="<?php echo($mod->get_cost())?>" onkeyup="testModForm();"/> zł</td>
  </tr>
  <tr>
    <td>Typ instalacji</td>
    <td>
    <select name="inst" id="inst" onchange="testModForm();">
      <option></option>
      <?php 
      $inst = $mod->get_inst();
      foreach($inst_arr as $key=>$val)
      {
        if($key==$inst)
          echo "<option value=\"$key\" selected>$val</option>";
        else
          echo "<option value=\"$key\">$val</option>";
      }?>
    </select>
    </td>
  </tr>
  <tr>
    <td>Rodzaj przeróbki</td>
    <td>
    <select name="type" id="type" onchange="testModForm();">
      <option></option>
      <?php 
      $type = $mod->get_type();
      foreach($type_arr as $key=>$val)
      {
        if($key==$type)
          echo "<option value=\"$key\" selected>$val</option>";
        else
          echo "<option value=\"$key\">$val</option>";
      }?>
    </select>
    </td>
  </tr>
  <tr>
    <td>Miejsce przeróbki</td>
    <td>
    <select name="cause" id="cause" onchange="testModForm();">
      <?php 
      $cause = $mod->get_cause();
      foreach($cause_arr as $key=>$val)
      {
        if($key==$cause)
          echo "<option value=\"$key\" selected>$val</option>";
        else
          echo "<option value=\"$key\">$val</option>";
      }?>
    </select>
    </td>
  </tr>
  <tr>
    <td>Lokalizacja</td>
    <td>
    <?php if($loc_arr):
    echo($loc_arr['str']);
    ?>
    <input type="hidden" name="loc_id" value="<?php echo($loc_arr['id']); ?>" />
    <?php else: ?>
    <select name="street" id="street" onchange="testModForm();">
      <option></option>
    <?php foreach ($streets as $street)
      echo '<option value="'.$street['ULIC'].'">'.$street['short_name']."</option>\n";
    ?>
    </select><br />
    <input type="text" name="building" id="building" style="width:50px" value=""  onkeyup="testModForm();"/> /<input type="text" name="flat" id="flat" style="width:50px" value=""  onkeyup="testModForm();"/>
    Niepowiązany z Abonentem
    <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td>Info</td>
    <td><textarea name="desc" cols="30" rows="5"><?php echo($mod->get_desc())?></textarea></td>
  </tr>
  <tr>
    <td>
    </td>
    <td><input type="submit" value="Zapisz" id="save_button"/>
    </td>
  </tr>
  </table>
    <input type="hidden" name="id" value="<?php echo($mod->get_id()); ?>"/>
    <input type="hidden" name="con_id" value="<?php echo(intval($con_id)); ?>"/>
  </form>
</table>
</div>
<script type="text/javascript">testModForm();</script>
<?php
$form_target = 'modyfications_form.php?tryb=modyfications&mod_id='.$mod_id.'&con_id='.$con_id;
require(LISTA_ABSOLUTE.'/include/html/modyfications.php');
?>
</body>
</html>
