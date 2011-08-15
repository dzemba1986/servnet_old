<?php require("security.php"); ?>
<tr>
	<td>Numer seryjny *</td>
	<td><input class="" type="text" name="sn" value="<? echo($device['sn']); ?>"></td>
</tr>
<tr>
	<td>Producent *</td>
	<td><select class="" name="producent" id="producent" value=""></td>
</tr>
<tr>
	<td>Model *</td>
	<td><select class="" name="model" id="model" value=""></td>
</tr>
<tr>
	<td>Liczba portów *</td>
	<td><input class="" type="text" name="port_count" value="<?php echo($device['port_count']); ?>"></td>
</tr>
<?php require("formularz_skrypt_producent.php");?>
