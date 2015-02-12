<?php require("security.php");
?>
<?php echo $device['device_type'];
$device_type = $device['device_type']; ?>
<form action="magazyn.php" method="post">
<table class="tabela_danych_wejsciowych">
<tr bgcolor="#D8D8D8">
	<td>Adres MAC *</td>
	<td><input class="" type="text" name="mac" value="<?php echo($device['mac']); ?>" readonly>
	</td>
</tr>
<tr bgcolor="#D8D8D8">
	<td>Nazwa inna</td>
	<td><input class="" type="hidden" name="dev_id" value="<?php echo($device['dev_id']); ?>">
		<input class="" type="text" name="other_name" value="<?php echo($device['other_name']); ?>"></td>
</tr>
<tr bgcolor="#D8D8D8">
	<td>Opis urządzenia</td>
	<td><textarea class="" type="text" name="opis" style="width:380px"><?php echo($device['opis']); ?></textarea>
	</td>
</tr>
<tr>
	<td>Opis zdarzenia</td>
	<td><textarea class="" type="text" name="opis_historii" style="width:380px"><?php echo($device['opis_historii']); ?></textarea>
	</td>
</tr>
<tr>
  <td colspan="3">
  Historia
  <table class="historia_table">
<?php
$daddy = new Daddy();
$history = $daddy->getHistoryArray($device['dev_id'], $device['lokalizacja']);
if($history)
{
  echo"<tr><td>czas</td><td>lokalizacja</td><td>opis</td><td>autor</td></tr>";
  foreach($history as $wpis)
  {
    echo"<tr><td>".$wpis['data_pl']."</td><td>".$wpis['lokalizacja1']."</td><td>".$wpis['opis']."</td><td>".$wpis['autor']."</td></tr>";
  }
}
?>  
  </table>
  </td> 
</tr>
