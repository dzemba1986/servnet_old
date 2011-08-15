<?php require("security.php"); ?>
<tr>
	<td>Numer seryjny *</td>
	<td><input class="" type="text" name="sn" value="<? echo($device['sn']); ?>" <?php if($device['dev_id']) echo 'disabled';?>></td>
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
