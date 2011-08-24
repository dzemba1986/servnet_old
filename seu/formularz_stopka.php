<?php require("security.php"); ?>
<tr>
	<td>&nbsp<input type="hidden" name="timestamp" value="<?php echo(time());?>"></td>
	<td><input class="" type="submit" value="<? if (isset($_GET['device'])) echo "Zachowaj zmiany"; else echo "dodaj"; ?>" name="dodaj"></td>
</tr>

</table>
</form>
</div>
<div id="device_menu">Device_menu</div>
</div>
<div id="dol"></div>
</div>
</body>
</html>
