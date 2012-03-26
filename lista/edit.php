<?php 
require('path.php');
require(LISTA_ABSOLUTE.'/include/html/header.php');
require(LISTA_ABSOLUTE.'/include/classes/installations.php');
require(LISTA_ABSOLUTE.'/include/classes/connections.php');
require(LISTA_ABSOLUTE.'/include/classes/phpToJs.php');
require(LISTA_ABSOLUTE.'/include/classes/localization.php');
require(LISTA_ABSOLUTE.'/include/classes/modyfications.php');
define('DADDY_PATH', SEU_ABSOLUTE.'/include/classes/daddy.php');
require(DADDY_PATH);
$connection = new Connections();
$installation = new Installations();
if($_REQUEST['field_name'])
{
  $field_name = $_REQUEST['field_name'];
  $id = $_REQUEST['id'];
  if($field_name == 'wire')
  {
    $wire_length = $_REQUEST['wire_length'];
    $wire_installation_date = $_REQUEST['wire_installation_date'];
    $wire_installer = $_REQUEST['wire_installer'];
    $installation->updateWire($id, $wire_length, $wire_installation_date, $wire_installer);
  }
  elseif($field_name == 'socket')
  {
    $socket_installation_date = $_REQUEST['socket_installation_date'];
    $socket_installer = $_REQUEST['socket_installer'];
    $installation->updateSocket($id, $socket_installation_date, $socket_installer);
  }
  elseif($field_name == 'add_installation')
  {
    $connection->addInstallation($id);
  }
  else
  {
    $field_value = $_REQUEST[$field_name];
    if($field_name =='installation_date')
    {
      $value2 = $_REQUEST['installation_time'];
      $connection->update($id, $field_name, $field_value, $value2);
    }
    elseif($field_name!='type')
    {
      $connection->update($id, $field_name, $field_value, null);
    }
    else
    {
      $installation->updateType($id, $field_value);
    }
  }
}
if($_REQUEST['main_id'])
{
  if($_REQUEST['phone_id'])
  {
    $sql = new myMysql();
    $connection1 = $sql->getConnection($_REQUEST['main_id']);
    $connection2 = $sql->getConnection($_REQUEST['phone_id']);
    $installation1 = $sql->getInstallation($connection1['address'], 'net');
    $installation2 = $sql->getInstallation($connection2['address'], 'phone');
//    print_r($installation2);
    //edycja internet+telefon
  }
  else
  {
    $sql = new myMysql();
    $connection1 = $sql->getConnection($_REQUEST['main_id']);
    $installation1 = $sql->getInstallation($connection1['address'], $connection1['service']);
    if($connection1['modyfication'])
      $modyfication1 = Modyfications::getById($connection1['modyfication']);
//    print_r($installation1);
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
  foreach($switches_loc_array as $s_loc)
  {
    if($s_loc['id_lok']!=$base_switch_loc)
       $switches_loc_opt .= "<option value=\"".$s_loc['id_lok']."\">".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
    else
       $switches_loc_opt .= "<option value=\"".$s_loc['id_lok']."\" selected>".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
  }
  $voip_gw_loc;
  if($_REQUEST['phone_id'])
  {
    if($connection2['switch_loc'])
      $voip_gw_loc = $daddy->getSwitchLocString($connection2['switch_loc']);
    $base_voip_gw_loc = $daddy->getSwitchLoc($loc_array['ulic'], $loc_array['blok'], $loc_array['mieszkanie']);
    $voip_gw_loc_opt;
    foreach($switches_loc_array as $s_loc)
    {
      if($s_loc['id_lok']!=$base_voip_gw_loc)
         $voip_gw_loc_opt .= "<option value=\"".$s_loc['id_lok']."\">".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
      else
         $voip_gw_loc_opt .= "<option value=\"".$s_loc['id_lok']."\" selected>".$s_loc['short_name'].$s_loc['nr_bloku'].$s_loc['klatka']." ".$s_loc['other_name']." (".$s_loc['ip'].")</option>";
    }
  }

}
$daddy = new Daddy();
$dev_id = $daddy->getDevId($connection1['id']);

?>
<script type="text/javascript" src="js/edit.js"></script>
<div style="clear:both;"></div>
<div id="net">
<center><div id="net_connection">
<div class="edit_little_header"><?php if($connection1['service']=="net") echo "Internet"; else echo "Telefon";?></div>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data dodania</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_start_date'])?>" name="start_date" id="start_date_1" onkeyup="testStartDate(this);"><?php echo($connection1['a_user'])?></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="start_date"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<table class="tables">
  <tr height="25">
    <td width="160">Deadline</td>
    <td width="185"> <?php echo($connection1['_end_date'])?></td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>ID</td>
    <td> <?php echo($connection1['id'])?></td>
    <td><?php if($connection->deletable()) 
        {
            echo"<script type=\"text/javascript\" src=\"js/delete.js\"></script>";
            echo"<button class=\"submit_field\" style=\"size:18px; color: red; font-weight:bold\" onclick=\"deleteObject(this, ".$connection1['id'].", 'con');\">USUŃ</button>";
        }
        ?>
    </td>
  </tr>
  <tr>
    <td>ARA ID</td>
    <td> <?php echo($connection1['ara_id'])?></td>
    <td>&nbsp; </td>
  </tr>
</table>
<!--<form action="edit.php?tryb=edit" method="post">-->
<table class="tables">
  <tr>
    <td width="160">Adres</td>
    <td width="185"><input class="address_field" type="text" value="<?php echo $address ?>" name="address" id="connection_address_1" onkeyup="testAddress(this);"></td>
    <td><?php if($connection->deletable())
{
 echo"<script type=\"text/javascript\" src=\"js/change_address.js\"></script>";
 $phpToJs = new PhpToJs();
 $ulic = $sql->getUlic();
 $osiedla = array();
 foreach($ulic as $ulica)
   $osiedla[$ulica['ULIC']] = $ulica['street_name'];
 $phpToJs->add_array($osiedla, osiedla, 2);
 echo($phpToJs->output_all());
 echo"<button class=\"submit_field\" onclick=\"addressForm(this, ".$connection1['id'].", osiedla);\">Zmień</button>";
} ?> 
    </td>
  </tr>
</table>
<!--</form>-->
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr height="25">
    <td width="160">Przełącznik</td>
    <?php if(!$dev_id): ?>
    <td width="185"><?php echo($connection1['switch'])?><input class="switch_field" type="text" value="<?php echo($switch_loc)?>" name="switch" id="switch_1" onkeyup="testSwitch(this);"><select name="switch_loc"><option></option><?php echo $switches_loc_opt ?></select></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="switch_loc"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
    <?php else: ?>
    <td width="185"><?php echo($daddy->getParentDeviceString($dev_id)) ?></td>
    <td></td>
    <?php endif; ?>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr height="25">
    <td width="160">Port</td>
    <?php if(!$dev_id): ?>
    <td width="185"><select class="port_field" name="port" id="port_1" onchange="changedField(this);">
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
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="port"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
    <?php else: ?>
    <td width="185"><?php echo($daddy->getParentPortsString($dev_id)) ?></td>
    <td></td>
    <?php endif; ?>
  </tr>
</table>
</form>
<?php if($connection1['service']=="net"):?>
<form action="edit.php?tryb=edit" onsubmit="return checkMacEditForm('<?php echo($connection1['id'])?>');" method="post">
<table class="tables">
  <tr height="25">
    <td width="160">mac</td>
    <?php if(!$dev_id): ?>
    <td width="185">
      <script type="text/javascript" src="js/checkMac.js"></script>
      <input class="mac_field" type="text" value="<?php echo($connection1['mac'])?>" name="mac" id="mac_1" onkeyup="testMac(this);"><?php echo ($mac_link); ?></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="mac"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
    <?php else: ?>
    <td width="185"><?php echo($daddy->getDeviceMac($dev_id)) ?></td>
    <td></td>
    <?php endif; ?>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr height="25">
    <td width="160">prędkość</td>
    <?php if(!$dev_id): ?>
    <td width="185"><input class="speed_field" type="text" value="<?php echo($connection1['speed'])?>" name="speed" id="speed_1" onkeyup="testSpeed(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="speed"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
    <?php else: 
    include(SEU_ABSOLUTE.'/include/classes/host.php');
    $host = new Host();
    $speed = $host->getSpeed($dev_id); ?> 
    <td width="185"><?php echo($speed) ?></td>
    <td></td>
    <?php endif; ?>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data konfiguracji</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_service_configuration'])?>" name="service_configuration" id="service_configuration_1" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="service_configuration"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php endif; ?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Poinformowano abonenta</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_informed'])?>" name="informed" id="informed_1" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="informed"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data aktywacji usługi</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_service_activation'])?>" name="service_activation" id="service_activation_1" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="service_activation"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
</tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Metoda weryfikacji</td>
    <td width="185"><select style="float:left;" name="veryfication_method" id="veryfication_method_1" onchange="changedField(this);">
      <option value=""></option>
      <option value="phone" <?php if($connection1['veryfication_method']=="phone") echo"selected";?>>Telefonicznie</option>
      <option value="dhcp" <?php if($connection1['veryfication_method']=="dhcp") echo"selected";?>>DHCP</option>
      <option value="personal" <?php if($connection1['veryfication_method']=="personal") echo"selected";?>>Osobiście</option>
    </select>
    </td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="veryfication_method"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php if($connection1['service']=='net' && !$connection1['service_configuration']): ?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td colspan="3"><center><a target="_blank" style="color: black; text-decoration:none; font-weight:bold; font-size: 10px; font-family: Verdana;" href="https://172.20.7.250/dodaj_z_listy.php?con_id=<?php echo($connection1['id']);?>">Dodaj do bazy</a></center></td>
  </tr>
</table>
</form>
<?php else: ?>
<table class="tables">
  <tr>
    <td colspan="3"><center><a target="_blank" style="color: black; text-decoration:none; font-weight:bold; font-size: 10px; font-family: Verdana;" href="https://172.20.7.250/tree.php?con_id=<?php echo($connection1['id']);?>">SEU</a></center></td>
  </tr>
</table>
<?php endif;?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Konfigurował</td>
    <td width="185"><?php echo($connection1['c_user'])?></td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Płatności od</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_payment_activation'])?>" name="payment_activation" id="payment_activation_1" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="payment_activation"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Rezygnacja</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection1['_resignation_date'])?>" name="resignation_date" id="resignation_date_1" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="resignation_date"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Telefon #1</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection1['phone'])?>" name="phone" id="phone_1" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="phone"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">telefon #2</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection1['phone2'])?>" name="phone2" id="phone2_1" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="phone2"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">telefon #3</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection1['phone3'])?>" name="phone3" id="phone3_1" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="phone3"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Montaż</td>
    <td width="185"><?php if($modyfication1) echo $modyfication1->get_s_date().' '.$modyfication1->get_s_time() ; ?></td>
    <td >      <a href="modyfications_form.php?tryb=modyfications&con_id=<?php echo($connection1['id'])?>&phone_id=<?php echo($connection2['id'])?>">Zmień</a>
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Ostatnia modyfikacja</td>
    <td><?php echo($connection1['last_modyfication'])?></td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Dodatkowe info</td>
    <td><textarea rows="8" cols="30" class="info_field" name="info" id="info_1" onkeyup="changedField(this);"><?php echo($connection1['info'])?></textarea></td>
    <td><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="info"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Boa info</td>
    <td><textarea rows="8" cols="30" class="info_field" name="info_boa" id="info_3" onkeyup="changedField(this);"><?php echo($connection1['info_boa'])?></textarea></td>
    <td><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="info_boa"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
</div>
<div id="net_installation">
<div class="edit_little_header"><?php if($connection1['service']=="net") echo "Internet"; else echo "Telefon";?> - instalacja</div>
<?php if($installation1['installation_id']): ?>
<table class="tables">
  <tr>
    <td width="160">ID</td>
    <td width="185"> <?php echo($installation1['installation_id'])?></td>
    <td><?php if($connection->deletable()) 
        {
            echo"<button class=\"submit_field\" style=\"size:18px; color: red; font-weight:bold\" onclick=\"deleteObject(this, ".$installation1['installation_id'].", 'inst');\">USUŃ</button>";
        }
        ?>
    </td>
  </tr>
</table>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">długość przewodu</td>
    <td width="185"><input class="address_field" type="text" onkeyup="testEditWireForm('1');" value="<?php echo($installation1['wire_length'])?>" name="wire_length" id="wire_length_1"></td>
    <td rowspan="3" ><input type="hidden" name="id" value="<?php echo($installation1['installation_id'])?>"><input type="hidden" name="field_name" value="wire"><input type="submit" class="submit_field"  id="wire_save_button_1" value="zmień"></td>
  </tr>
  <tr>
    <td width="160">Data doprowadzenia przewodu</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($installation1['_wire_installation_date'])?>" name="wire_installation_date" id="wire_installation_date_1" onkeyup="testEditWireForm('1');"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testEditWireForm('1');">Dziś</div></td>
  </tr>
  <tr>
    <td width="160">Przewód zamontował</td>
    <td width="185"><input class="installer_field" name="wire_installer" id="wire_installer_1" onkeyup="testEditWireForm('1');" value="<?php echo($installation1['wire_installer'])?>">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data zamontowania gniazdka</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($installation1['_socket_installation_date'])?>" name="socket_installation_date" id="socket_installation_date_1" onkeyup="testEditSocketForm('1');"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testEditSocketForm('1');">Dziś</div></td>
    <td rowspan="2" ><input type="hidden" name="id" value="<?php echo($installation1['installation_id'])?>"><input type="hidden" name="field_name" value="socket"><input type="submit" class="submit_field"  id="socket_save_button_1" value="zmień"></td>
  </tr>
  <tr>
    <td width="160">Gniazdko zamontował</td>
    <td width="185"><input class="installer_field" name="socket_installer" id="socket_installer_1" onkeyup="testEditSocketForm('1');" value="<?php echo($installation1['socket_installer'])?>">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Typ instalacji</td>
    <td width="185"><select style="float:left;" name="type" id="type_1" onchange="changedField(this)">
      <option value="net">Internet</option>
      <option value="phone" <?php if($installation1['type']=='phone') echo "selected";?>>Telefon</option>
    </select></td>
    <td ><input type="hidden" name="id" value="<?php echo($installation1['installation_id'])?>"><input type="hidden" name="field_name" value="type"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php else: ?>
Brak instalacji
<form action="edit.php?tryb=edit" method="post">
<input type="hidden" name="timestamp" value="<?php echo(time())?>"><input type="hidden" name="id" value="<?php echo($connection1['id'])?>"><input type="hidden" name="field_name" value="add_installation"><input type="submit" class="submit_field"  value="Dodaj">
<input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
</form>
<?php endif; ?>
</div>
</center>
</div>
<?php if($_REQUEST['phone_id']): ?>
<div id="phone">
<center><div id="phone_connection">
<div class="edit_little_header"><?php if($connection2['service']=="net") echo "Internet"; else echo "Telefon";?></div>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data dodania</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_start_date'])?>" name="start_date" id="start_date_2" onkeyup="testStartDate(this);"><?php echo($connection2['a_user'])?></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="start_date"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<table class="tables">
  <tr>
    <td width="160">Deadline</td>
    <td width="185"> <?php echo($connection2['_end_date'])?></td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>ID</td>
    <td> <?php echo($connection2['id'])?></td>
    <td><?php if($connection->deletable()) 
        {
            echo"<script type=\"text/javascript\" src=\"js/delete.js\"></script>";
            echo"<button class=\"submit_field\" style=\"size:18px; color: red; font-weight:bold\" onclick=\"deleteObject(this, ".$connection2['id'].", 'con');\">USUŃ</button>";
        }
        ?>
    </td>
  </tr>
  <tr>
    <td>ARA ID</td>
    <td> <?php echo($connection2['ara_id'])?></td>
    <td>&nbsp; </td>
  </tr>
</table>
<!--<form action="edit.php?tryb=edit" method="post">-->
<table class="tables">
  <tr>
    <td width="160">Adres</td>
    <td width="185"><input class="address_field" type="text" value="<?php echo $address ?>" name="address" id="connection_address_2" onkeyup="testAddress(this);"></td>
    <td><?php if($connection->deletable())
{
 echo"<script type=\"text/javascript\" src=\"js/change_address.js\"></script>";
 $phpToJs = new PhpToJs();
 $ulic = $sql->getUlic();
 $osiedla = array();
 foreach($ulic as $ulica)
   $osiedla[$ulica['ULIC']] = $ulica['street_name'];
 $phpToJs->add_array($osiedla, osiedla, 2);
 echo($phpToJs->output_all());
 echo"<button class=\"submit_field\" onclick=\"addressForm(this, ".$connection2['id'].", osiedla);\">Zmień</button>";
} ?> 
    </td>
  </tr>
</table>
<!--</form>-->
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Przełącznik</td>
    <td width="185"><?php echo($connection2['switch'])?><input class="switch_field" type="text" value="<?php echo($voip_gw_loc)?>" name="switch" id="switch_2" onkeyup="testSwitch(this);"><select name="switch_loc"><option></option><?php echo $voip_gw_loc_opt ?></select></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="switch_loc"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Port</td>
    <td width="185"><select class="port_field" name="port" id="port_2" onchange="changedField(this);">
      <option></option>
      <?php for($i=1; $i<=46; $i++)
      {
        if($connection2['port']==$i)
          echo "<option selected>$i</option>";
        else
          echo "<option>$i</option>";
      } ?>
    </select>
    </td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="port"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php if($connection2['service']=="net"): ?>
<form action="edit.php?tryb=edit" onsubmit="return checkMacEditForm('<?php echo($connection2['id'])?>');" method="post">
<table class="tables">
  <tr>
    <td width="160">mac</td>
    <td width="185"><input class="mac_field" type="text" value="<?php echo($connection2['mac'])?>" name="mac" id="mac_2" onkeyup="testMac(this);"><?php echo ($mac_link); ?></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="mac"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">prędkość</td>
    <td width="185"><input class="speed_field" type="text" value="<?php echo($connection2['speed'])?>" name="speed" id="speed_2" onkeyup="testSpeed(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="speed"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data konfiguracji</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_service_configuration'])?>" name="service_configuration" id="service_configuration_2" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="service_configuration"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php endif; ?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Poinformowano abonenta</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_informed'])?>" name="informed" id="informed_2" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="informed"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data aktywacji usługi</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_service_activation'])?>" name="service_activation" id="service_activation_2" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="service_activation"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
</tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Metoda weryfikacji</td>
    <td width="185"><select style="float:left;" name="veryfication_method" id="veryfication_method_2" onchange="changedField(this);">
      <option value=""></option>
      <option value="phone" <?php if($connection2['veryfication_method']=="phone") echo"selected";?>>Telefonicznie</option>
      <option value="dhcp" <?php if($connection2['veryfication_method']=="dhcp") echo"selected";?>>DHCP</option>
      <option value="personal" <?php if($connection2['veryfication_method']=="personal") echo"selected";?>>Osobiście</option>
    </select>
    </td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="veryfication_method"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php if($connection2['service']=='net' && !$connection2['service_configuration']): ?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td colspan="3"><center><a target="_blank" style="color: black; text-decoration:none; font-weight:bold; font-size: 10px; font-family: Verdana;" href="https://172.20.7.250/8000GS/add_internet.php?mac=<?php echo($connection2['mac']."&amp;address=".$connection2['address']."&amp;speed=".$connection2['speed']);?>">Konfiguruj</a></center></td>
  </tr>
</table>
</form>
<?php endif;?>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Konfigurował</td>
    <td width="185"><?php echo($connection2['c_user'])?></td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Płatności od</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_payment_activation'])?>" name="payment_activation" id="payment_activation_2" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="payment_activation"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Rezygnacja</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($connection2['_resignation_date'])?>" name="resignation_date" id="resignation_date_2" onkeyup="testDate(this);"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testDate(this.previousSibling);">Dziś</div></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="resignation_date"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Telefon #1</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection2['phone'])?>" name="phone" id="phone_2" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="phone"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">telefon #2</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection2['phone2'])?>" name="phone2" id="phone2_2" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="phone2"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">telefon #3</td>
    <td width="185"><input class="phone_field" type="text" value="<?php echo($connection2['phone3'])?>" name="phone3" id="phone3_2" onkeyup="testPhone(this);"></td>
    <td ><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="phone3"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Ostatnia modyfikacja</td>
    <td><?php echo($connection2['last_modyfication'])?></td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Dodatkowe info</td>
    <td><textarea rows="8" cols="30" class="info_field" name="info" id="info_2" onkeyup="changedField(this);"><?php echo($connection2['info'])?></textarea></td>
    <td><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="info"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Boa info</td>
    <td><textarea rows="8" cols="30" class="info_field" name="info_boa" id="info_4" onkeyup="changedField(this);"><?php echo($connection2['info_boa'])?></textarea></td>
    <td><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="info_boa"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
</div>
<div id="phone_installation">
<div class="edit_little_header"><?php if($connection2['service']=="net") echo "Internet"; else echo "Telefon";?> - instalacja</div>
<?php if($installation2['installation_id']): ?>
<table class="tables">
  <tr>
    <td width="160">ID</td>
    <td width="185"> <?php echo($installation2['installation_id'])?></td>
    <td><?php if($connection->deletable()) 
        {
            echo"<button class=\"submit_field\" style=\"size:18px; color: red; font-weight:bold\" onclick=\"deleteObject(this, ".$installation2['installation_id'].", 'inst');\">USUŃ</button>";
        }
        ?>
    </td>
  </tr>
</table>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">długość przewodu</td>
    <td width="185"><input class="address_field" type="text" onkeyup="testEditWireForm('2');" value="<?php echo($installation2['wire_length'])?>" name="wire_length" id="wire_length_2"></td>
    <td rowspan="3" ><input type="hidden" name="id" value="<?php echo($installation2['installation_id'])?>"><input type="hidden" name="field_name" value="wire"><input type="submit" class="submit_field"  id="wire_save_button_2"  value="zmień"></td>
  </tr>
  <tr>
    <td width="160">Data doprowadzenia przewodu</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($installation2['_wire_installation_date'])?>" name="wire_installation_date" id="wire_installation_date_2" onkeyup="testEditWireForm('2');"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testEditWireForm('2');">Dziś</div></td>
  </tr>
  <tr>
    <td width="160">Przewód zamontował</td>
    <td width="185"><input class="installer_field" name="wire_installer" id="wire_installer_2" onkeyup="testEditWireForm('2');" value="<?php echo($installation2['wire_installer'])?>">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Data zamontowania gniazdka</td>
    <td width="185"><input class="date_field" type="text" value="<?php echo($installation2['_socket_installation_date'])?>" name="socket_installation_date" id="socket_installation_date_2" onkeyup="testEditSocketForm('2');"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testEditSocketForm('2');">Dziś</div></td>
    <td rowspan="2" ><input type="hidden" name="id" value="<?php echo($installation2['installation_id'])?>"><input type="hidden" name="field_name" value="socket"><input type="submit" class="submit_field"  id="socket_save_button_2" value="zmień"></td>
  </tr>
  <tr>
    <td width="160">Gniazdko zamontował</td>
    <td width="185"><input class="installer_field" name="socket_installer" id="socket_installer_2" onkeyup="testEditSocketForm('2');" value="<?php echo($installation2['socket_installer'])?>">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<form action="edit.php?tryb=edit" method="post">
<table class="tables">
  <tr>
    <td width="160">Typ instalacji</td>
    <td width="185"><select style="float:left;" name="type" id="type_2" onchange="changedField(this)">
      <option value="net">Internet</option>
      <option value="phone" <?php if($installation2['type']=='phone') echo "selected";?>>Telefon</option>
    </select></td>
    <td ><input type="hidden" name="id" value="<?php echo($installation2['installation_id'])?>"><input type="hidden" name="field_name" value="type"><input type="submit" class="submit_field"  value="zmień">
      <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
      <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
    </td>
  </tr>
</table>
</form>
<?php else: ?>
Brak instalacji
<form action="edit.php?tryb=edit" method="post">
<input type="hidden" name="timestamp" value="<?php echo(time())?>"><input type="hidden" name="id" value="<?php echo($connection2['id'])?>"><input type="hidden" name="field_name" value="add_installation"><input type="submit" class="submit_field"  value="Dodaj">
<input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
</form>
<?php endif; ?>
</div>
</center>
</div>
<? endif;?>
</body>
</html>
