<?php
  header('Content-Type: text/html; charset=utf-8');
?>

<html>
  <head>
    <title>Formularz HTML</title>
  </head>
    <body>
      <a href="index.php"><button>Powrót</button></a><br><br>
      <form method="post" action="przenosiny.php">
       <table>

			<tr><td><label>Usługa</label></td><td><select name="usl">
				<option>net</option>
				<option>phone</option>
			</select></td></tr>
			<tr><td><label>Osiedle</label></td><td><select name="osiedle">
				<option>OK</option>
				<option>OP</option>
				<option>OPL</option>
				<option>OWW</option>
				<option>OZ</option>
			</select></td></tr>
			<tr><td><label>Numer bloku</label></td><td><input name="blok" /></td></tr>
			<tr><td><label>Nr pierwszego mieszkania</label></td><td><input type="number" name="mieszkanie_start" /></td></tr>
			<tr><td><label>Nr ostatniego mieszkania</label></td><td><input type="number" name="mieszkanie_end" /></td></tr>
			<tr><td><label>Początek numeracji portów</label></td><td><input type="number" name="startowy_port" /></td></tr>
			<tr><td></td><td><input type="submit" value="OK"/>  </td></tr>
		</table>
      </form>
      
     
    </body>
</html>


