<?php require("security.php"); ?>
<script type="text/javascript">
pobierzProducent();
setTimeout("aktywujProducent(<?php echo($device['producent']); ?>)",300);
<?php if($device['device_type'] && $device['producent'])
echo'	pobierzModel("'.$device['device_type']."\", ".$device['producent'].' );
	setTimeout("aktywujModel(\''.$device['model'].'\')",400);';
?>
</script>
