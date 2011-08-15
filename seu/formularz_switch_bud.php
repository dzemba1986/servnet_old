<?php require("security.php"); ?>
<tr>
	<td>Numer seryjny *</td>
	<td><input class="" type="text" name="sn" value="<?php echo($device['sn']); ?>" <?php //if($device['dev_id']) echo 'disabled';?>></td>
</tr>
<tr>
	<td>Producent *</td>
	<td><select class="" name="producent" id="producent" value="" <?php if($device['dev_id']) echo 'disabled';?>></td>
</tr>
<tr>
	<td>Model *</td>
	<td><select class="" name="model" id="model" value="" <?php if($device['dev_id']) echo 'disabled';?>></td>
</tr>
<?php require("formularz_skrypt_producent.php");?>
<tr>
	<td>Typ *</td>
	<td><select name="typ" >
		<option value="budynek" <?php if($device['typ']=="budynkowy") echo("selected");?>>Switch budynkowy</option>
		<option value="segment" <?php if($device['typ']=="segmentowy") echo("selected");?>>Switch segmentowy</option>
	</select></td>
</tr>
