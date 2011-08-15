<?php require("security.php"); ?>
<tr>
	<td>Numer mieszkania *</td>
	<td><input class="" type="text" name="nr_mieszkania" value="<? echo($device['nr_mieszkania']); ?>"></td>
</tr>
<tr>
	<td>Id z listy *</td>
	<td><input class="" type="text" name="con_id" value="<? echo($device['con_id']); ?>"></td>
</tr>
<tr>
	<td>Pakiet *</td>
	<td><select name="pakiet">
	<?php 
	$daddy = new Daddy();
	$pakiety = $daddy->pobierzPakiety();
	foreach($pakiety as $pakiet)
	if($device['pakiet']==$pakiet['id'])
		echo "<option value=\"".$pakiet['id']."\" selected>".$pakiet['nazwa_pakietu']."</option>";
	else
	echo "<option value=\"".$pakiet['id']."\">".$pakiet['nazwa_pakietu']."</option>";
	?>
	</select> 
	</td>
</tr>
<tr>
	<td>Data uruchomienia</td>
	<td><input class="" type="text" name="data_uruchomienia" value="<? echo($device['start']); ?>"> dd.mm.yy</td>
</tr>
<?php if($device['dev_id']){ ?>
<tr>
	<td>Data zakonczenia</td>
	<td><input class="" type="text" name="data_zakonczenia" value="<? echo($device['stop']); ?>"> dd.mm.yy</td>
</tr>
<?php }
