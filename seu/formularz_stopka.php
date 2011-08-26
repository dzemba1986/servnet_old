<?php require("security.php"); 
if($device_type):
?>
<tr>
	<td>&nbsp<input type="hidden" name="timestamp" value="<?php echo(time());?>"></td>
	<td><input class="" type="submit" value="<? if (isset($_GET['device'])) echo "Zachowaj zmiany"; else echo "dodaj"; ?>" name="dodaj"></td>
</tr>

</table>
</form>
<?php endif?>
</div>
<?php if($device_type): ?>
<div id="device_menu">Device_menu</div>
<?php endif?>
</div>
<div id="dol"></div>
</div>
</body>
</html>
