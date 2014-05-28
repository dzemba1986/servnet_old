<?php require("security.php");
$lok = new Lokalizacja();
$lokalizacje = $lok->getUlic();
?>
<script type="text/javascript">
//zmienne globalne
var DEV_ID<?php if($device['dev_id']) echo(" =". $device['dev_id']); ?>;
var IPLISTA = false;
var PARENT_DEVICE<?php if($device['parent_device']) echo(" =". $device['parent_device']); ?>;
var LOCAL_DEV_MODEL = '<?php if($device['model']) echo ($device['model']);?>';
var PARENT_PORTS;
var LOCAL_PORTS;
var UPLINK_CONNECTIONS;
var DEVICE_TYPE = '<?php if($centralny) echo 'Switch_centralny'; elseif($device_type) echo ($device_type); else echo("document.getElementsByName('device_type')[0].value");?>';
function activateParentDevice(value)
{
	PARENT_DEVICE=value;
	if(value && LOCAL_DEV_MODEL)
		getDeviceUplink(value, LOCAL_DEV_MODEL);
	else if(DEVICE_TYPE=='Host' || DEVICE_TYPE=='Virtual')
		if(DEV_ID)
			getHostUplink(value, DEV_ID);
		else
			getHostUplink(value);
}
</script>
<?php 
$daddy = new Daddy();
$parent_id1 = $daddy->getDeviceModel($device['parent_device']);
$parent_id = $parent_id1['id'];
?>

<?php if($device['dev_id']): ?>
<table class="tabela_danych_wejsciowych">
<tr>
	<td class="tabela_danych_wejsciowych_label" style="background: #999999; border: 1px solid black; border-right: none;">Adres MAC *</td>
	<td style="background: #999999; border: 1px solid black; border-left: none;"><input class="" type="text" name="mac" id="mac" value="<?php echo($device['mac']); ?>" readonly>
	<button onclick="changeMac(this,'<?php echo($device['dev_id']); ?>',document.getElementById('mac').value, document.getElementsByName('_vlan1')[0].value, '<?php echo($parent_id); ?>', document.getElementsByName('_ip1')[0].value); return false;" style=" margin-left: 30px;">Zmień</button></td>
</tr>
</table>
<?php if($device_type):?>

<form method="post" action="<?php echo $form_action; ?>">
<input type="hidden" name="device_type" value="<?php  echo($device_type);?>">
<table class="tabela_danych_wejsciowych">
<?php endif; ?>
<?php
else :?>
<?php if($device_type):?>

<form method="post" action="<?php echo $form_action; ?>">
<input type="hidden" name="device_type" value="<?php  echo($device_type);?>">
<table class="tabela_danych_wejsciowych">
<?php endif; ?>
<tr>
	<td class="tabela_danych_wejsciowych_label" style="background: gray; border: 1px solid black; border-right: none;">Adres MAC *</td>
	<td style="background: gray; border: 1px solid black; border-left: none;"><input class="" type="text" name="mac" id="mac" value="<?php echo($device['mac']); ?>" <?php if(isset($_GET['device']) && !$_GET['magazyn'] && $device['device_type']!='Host' && $device['mac']) echo "readonly"; ?>>
	</td>
</tr>

<?php endif;?>
<tr bgcolor="#D8D8D8">
	<td class="tabela_danych_wejsciowych_label" style="width:330px">Device exists *</td>
	<td><input class="" type="checkbox" name="exists" <?php if($device['exists'] || !$device['dev_id']) echo "checked";  ?>>
</tr>
<tr>
<td><input class="" type="hidden" name="dev_id" value="<?php echo($device['dev_id']); ?>">	
	Adresy IP *<br>(Pierwszy adres jest adresem głównym)</td>
<td>
<div>Vlan - Podsieć - IP</div>
	
<div id="vlans">
</div>
<input type="button" value="dodaj" onclick="dodajPole()">
<script type="text/javascript">
<? if(!$device['dev_id'])
	echo("dodajPole();");
   else
        echo("pobierzAdresy('".$device['dev_id']."');");
?>
</script>	

</td>
</tr>
<tr>
	<td>Nazwa inna</td>
	<td><input class="" type="text" name="other_name" value="<? echo($device['other_name']); ?>"></td>
</tr>
<?php if($device_type!='Virtual'):?>
<tr>
	<td>Brama domyślna</td>
	<td><input class="" type="text" name="gateway" id="gateway" value="<? echo($device['gateway']); ?>"></td>
</tr>
<?php endif;?>
<tr bgcolor="#D8D8D8">
	<td>Opis urządzenia</td>
	<td><textarea class="" type="text" name="opis" style="width:380px"><? echo($device['opis']); ?></textarea>
	</td>
</tr>
<tr>
	<td>Opis zdarzenia</td>
	<td><textarea class="" type="text" name="opis_historii" style="width:380px"><? echo($device['opis_historii']); ?></textarea>
	</td>
</tr>
<tr>
	<td>Urządzenie nadrzędne *</td>
	<td><select onchange="activateParentDevice(this.value);" name="parent_device">
	<option></option>
	<?php foreach($opcje as $opcja)
	{
		echo("<option value=\"".$opcja['dev_id']."\"");
		if($opcja['dev_id'] == $device['parent_device'])
			echo(" selected ");
		echo(">".$opcja['nazwa']."</option>");
	}
	?>

		</select></td>
</tr>
<tr>
	<td>Uplink *</td>
	<td><div id="uplinks"></div>
	<?php if (!$centralny)
	if ($device['dev_id'] && $device['parent_device']){ ?>
	<script type="text/javascript">
	if(DEVICE_TYPE=='Host' || DEVICE_TYPE=='Virtual' )
		getHostUplink('<?php echo($device['parent_device']); ?>','<?php echo($device['dev_id'])?>' );
	else
		getDeviceUplink('<?php echo($device['dev_id'])?>' );
	</script>
	<?php }
	else
	{ ?>
	<script type="text/javascript">
		if(PARENT_DEVICE && LOCAL_DEV_MODEL)
			getDeviceUplink(PARENT_DEVICE, LOCAL_DEV_MODEL);
	</script>
	<?php } ?></td>

</tr>
<tr>
	<td>Osiedle/Lokalizacja *</td>
	<td><select <?php echo "name=\"osiedle\""; ?>>
		<option></option>
	<?php foreach($lokalizacje as $lokalizacja)
        {	
          if($device['osiedle']==$lokalizacja['short_name'])
            echo "<option value=\"".$lokalizacja['ulic']."\" selected>".$lokalizacja['short_name']."</option>";
          else
            echo "<option value=\"".$lokalizacja['ulic']."\">".$lokalizacja['short_name']."</option>";
        }?>
		</select></td>
</tr>
<tr>
	<td>Nr bloku *</td>
	<td><input class="" type="text" name="blok" value="<? echo($device['nr_bloku']); ?>" ></td>
</tr>
<?php if($device_type!='Host'){ ?>
<tr>
	<td>Klatka schodowa</td>
	<td><input class="" type="text" name="klatka" value="<? echo($device['klatka']); ?>"></td>
</tr>
<?php } ?>
<tr bgcolor="#D8D8D8">
</tr>
