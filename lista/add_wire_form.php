<?php require('include/html/header.php'); 
require('include/classes/localization.php');
define('DADDY_PATH', SEU_ABSOLUTE.'/include/classes/daddy.php');
require(DADDY_PATH);
$tryb = $_GET['tryb'];
    require('include/classes/installations.php');
    require('include/classes/connections.php');
if($_REQUEST['field_name']=='add_installation')
{
    $connection_id = $_REQUEST['connection_id'];
    $connection = new Connections();
    $connection->addInstallation($connection_id);
  }
elseif($_REQUEST['change']==1)
{
    $installation_id = $_REQUEST['installation_id'];
    $connection_id = $_REQUEST['connection_id'];
    $wire_length = $_REQUEST['wire_length'];
    $wire_installation_date = $_REQUEST['wire_installation_date'];
    $wire_installer = $_REQUEST['wire_installer'];
    $switch_loc = $_REQUEST['switch_loc'];
    $port = $_REQUEST['port'];
    $info = $_REQUEST['info'];
    $installation = new Installations();
    $installation->updateWire($installation_id, $wire_length, $wire_installation_date, $wire_installer);
    $connection = new Connections();
    $connection->update($connection_id, 'switch_loc', $switch_loc, null);
    $connection->update($connection_id, 'port', $port, null);
    $connection->update($connection_id, 'info', $info, null);
}
if($_REQUEST['main_id'])
{
  if($_REQUEST['phone_id'])
  {
  }
  else
  {
    $sql = new myMysql();
    $connection1 = $sql->getConnection($_REQUEST['main_id']);
    $installation1 = $sql->getInstallation($connection1['address'], $connection1['service']);
    if(!$installation1['installation_id'])
    {
      $connection_id = $_REQUEST['main_id'];
      $connection_obj = new Connections();
      $connection_obj->addInstallation($connection_id);
      $installation1 = $sql->getInstallation($connection1['address'], $connection1['service']);
    }
//    print_r($installation1);
  }
}
  $loc = new Lokalizacja();
  $address = $loc->getAddressStr($connection1['localization']);
    $mac_dec = hexdec(preg_replace('/:/', '', $connection1['mac']));
    $mac_link = "<a class=\"header\" href=\"http://172.20.4.19/src/index.php?sourceid=3&amp;filter=clientmac%3A%3D$mac_dec&amp;search=Search\" target=\"_blank\">Syslog</a>";
  $daddy = new Daddy();
  $switch_loc;
  if($connection1['switch_loc'])
    $switch_loc = $daddy->getSwitchLocString($connection1['switch_loc']);
  $loc_array = $loc->getLoc($connection1['localization']);
  $base_switch_loc = $daddy->getSwitchLoc($loc_array['ulic'], $loc_array['blok'], $loc_array['mieszkanie']);
  $switches_loc_array = $daddy->getL2SwitchesLoc();
  $switches_loc_opt;
  echo $connection1['switch_loc'] ;
  foreach($switches_loc_array as $s_loc)
  {

    if($s_loc['id_lok']==$connection1['switch_loc'])
       $switches_loc_opt .= "<option value=\"".$s_loc['id_lok']."\" selected>".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
    elseif(!$connection1['switch_loc'] && $s_loc['id_lok']==$base_switch_loc)
       $switches_loc_opt .= "<option value=\"".$s_loc['id_lok']."\" selected>".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
    else
       $switches_loc_opt .= "<option value=\"".$s_loc['id_lok']."\">".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
  }


?>
<script type="text/javascript" src="js/edit.js"></script>
<div style="clear:both;"></div>
<div id="net">
<center>
<form method="POST" action="add_wire_form.php?tryb=edit">
<div id="net_connection">
    <div class="edit_little_header"><?php if($connection1['service']=="net") echo "Internet"; else echo "Telefon";?></div>
  <table class="tables">
  <tr>
  <td>Adres</td>
  <td><?php echo($connection1['address'])?></td>
  </tr>
  <tr>
  <td>Przełącznik</td>
    <td width="185"><?php echo($connection1['switch'])?><select name="switch_loc"><option></option><?php echo $switches_loc_opt ?></select></td>
  </tr>
  <tr>
  <td>Port</td>
  <td><select class="port_field" name="port" id="port_1">
      <option></option>
      <?php for($i=1; $i<=46; $i++)
      {
        if($connection1['port']==$i)
          echo "<option selected>$i</option>";
        else
          echo "<option>$i</option>";
      } ?>
  </select>
  </td>
  </tr>
  <tr>
  <td>Dodatkowe info</td>
  <td><textarea class="info_field" name="info" id="info_1" rows="8" cols="50"><?php echo($connection1['info'])?></textarea></td>
  </tr>
  </table>
</div>
<div id="net_installation">
  <?php if($installation1['installation_id']): ?>
  <table class="tables">
  <tr>
  <td>długość przewodu</td>
  <td><input title="Sama wartość numeryczna" class="address_field" type="text" onkeyup="testAddWireForm('<?php echo $connection1['service']?>');" value="<?php echo($installation1['wire_length'])?>" name="wire_length" id="wire_length_1"></td>
  </tr>
  <tr>
  <td>Data doprowadzenia przewodu</td>
  <td><input class="date_field" style="float:left;" type="text" onkeyup="testAddWireForm('<?php echo $connection1['service']?>');" value="<?php echo($installation1['_wire_installation_date'])?>" name="wire_installation_date" id="wire_installation_date_1"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>');testAddWireForm('<?php echo $connection1['service']?>');">Dziś</div></td>
  <tr>
  <td>Przewód zamontował</td>
  <td><input title="Minimum 2 znaki alfanumeryczne, dozwolone też &amp;,." class="installer_field" id="wire_installer_1" name="wire_installer" onkeyup="testAddWireForm('<?php echo $connection1['service']?>');" value="<?php echo($installation1['wire_installer'])?>"></td>
  </tr>
  </table>
  <input type="hidden" name="change" value="1">
  <input type="hidden" name="connection_id" value="<?php echo($connection1['id'])?>">
  <input type="hidden" name="installation_id" value="<?php echo($installation1['installation_id'])?>">
  <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
  <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
  <input type="submit" class="submit_field" id="save_button" value="zmień">
</div>
</form>
<script type="text/javascript">testAddWireForm('<?php echo $connection1['service']?>');</script>
<?php else: ?>
Brak instalacji
</div></form>
<form method="GET" action="add_wire_form.php">
<input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="add_installation"><input type="submit" class="submit_field" value="Dodaj">
<input type="hidden" name="connection_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
</form>
<?php endif; ?>
</center>
</div>
</body>
</html>
