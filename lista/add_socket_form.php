<?php require('include/html/header.php'); 
define('DADDY_PATH', SEU_ABSOLUTE.'/include/classes/daddy.php');
require(DADDY_PATH);
$tryb = $_GET['tryb'];
require('include/classes/installations.php');
require('include/classes/connections.php');
require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); 
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
    $mac = $_REQUEST['mac'];
    $socket_installation_date = $_REQUEST['socket_installation_date'];
    $socket_installer = $_REQUEST['socket_installer'];
    $service_activation = $_REQUEST['service_activation'];
    $info = $_REQUEST['info'];
    $installation = new Installations();
    $installation->updateSocket($installation_id, $socket_installation_date, $socket_installer);
    $connection = new Connections();
    $connection->update($connection_id, 'mac', $mac, null);
    if($service_activation)
      $connection->update($connection_id, 'service_activation', $service_activation, null);
    $connection->update($connection_id, 'info', $info, null);
    $connection->update($connection_id, 'installation_date', null, null);
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
$mod=null;
if($connection1['modyfication'])
  $mod = Modyfications::getById($connection1['modyfication']);
else
  $mod = false;


?>
<script type="text/javascript" src="js/edit.js"></script>
<script type="text/javascript" src="js/checkMac.js"></script>

<div style="clear:both;"></div>
<div id="net">
<center>
<form id="socket_form" method="POST" action="add_socket_form.php?tryb=edit">
<div id="net_connection">
    <div class="edit_little_header"><?php if($connection1['service']=="net") echo "Internet"; else echo "Telefon";?></div>
  <table class="tables">
  <tr>
  <td>Adres</td>
  <td><?php echo($connection1['address'])?></td>
  </tr>
<?php if($connection1['service']=="net"): ?>
  <tr>
  <td>MAC</td>
  <td><input class="mac_field" type="text" value="<?php echo($connection1['mac'])?>" name="mac" id="mac_1" onkeyup="testAddSocketForm('<?php echo $connection1['service']?>');"></td>
  </tr>
<?php endif;?>
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
  <td>Data zamontowania gniazdka</td>
  <td><input class="date_field" type="text" style="float:left;" value="<?php echo($installation1['_socket_installation_date'])?>" name="socket_installation_date" id="socket_installation_date_1" onkeyup="testAddSocketForm('<?php echo $connection1['service']?>');"><div style="float:left; cursor:pointer" onclick="setToday(this, '<?php echo(date("d.m.y")) ?>'); testAddSocketForm('<?php echo $connection1['service']?>');">Dziś</div></td>
  <tr>
  <td>Gniazdko zamontował</td>
  <td><input title="Minimum 2 znaki alfanumeryczne, dozwolone też &amp;,." class="installer_field" name="socket_installer" id="socket_installer_1" onkeyup="testAddSocketForm('<?php echo $connection1['service']?>');" value="<?php echo($installation1['socket_installer'])?>"></td>
  </tr>
  </table>
  <input type="hidden" name="change" value="1">
  <input type="hidden" id="connection_1" name="connection_id" value="<?php echo($connection1['id'])?>">
  <input type="hidden" name="installation_id" value="<?php echo($installation1['installation_id'])?>">
  <input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
  <input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
<?php if($connection1['modyfication']>0): ?>
</div>
</form>
  <button class="submit_field" id="save_button" onclick="modyficationCloseForm(document.getElementById('net'), '<?php echo ($mod->get_id()); ?>', document.getElementById('socket_installer_1').value, document.getElementById('info_1').value, '<?php echo ($mod->get_cost()); ?>', '<?php echo($connection1['id'])?>', true); return false;" style="width:120px;">Montaż wykonany</button>
  <button class="submit_field" id="save_button2" onclick="modyficationCloseFormNoSocket(document.getElementById('net'), '<?php echo ($mod->get_id()); ?>', document.getElementById('socket_installer_1').value, document.getElementById('info_1').value, '<?php echo ($mod->get_cost()); ?>', '<?php echo($connection1['id'])?>', false); return false;" style="width: 120px;">Montaż Niewykonany</button>
<?php else: ?>
  <input type="submit"  class="submit_field" id="save_button" value="Zmień">
</div>
</form>
<?php endif; ?>
<script type="text/javascript" src="js/closeModyfication.js"></script>
<script type="text/javascript">testAddSocketForm('<?php echo $connection1['service']?>');</script>
<?php else: ?>
Brak instalacji
</div></form>
<form method="GET" action="add_socket_form.php">
<input type="hidden" name="id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="field_name" value="add_installation">
<input type="submit" class="submit_field" value="Dodaj">
<input type="hidden" name="connection_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="main_id" value="<?php echo($connection1['id'])?>">
<input type="hidden" name="phone_id" value="<?php echo($connection2['id'])?>">
<input type="hidden" name="tryb" value="edit">
</form>
<?php endif; ?>
</center>
</div>
</body>
</html>
