<?php
  header('Content-Type: text/html; charset=utf-8');
?>

<html>
  <head>
    <title>Formularz HTML</title>
  </head>
    <body>
      <a href="index.php"><button>Powrót</button></a><br><br>
      <form method="post" action="switch.php">
       <table>
         <tr><td><label>Wybierz typ przełącznika:</label></td><td><input type="radio" name="switch_choice" value="24port" checked="checked" />24-portowy</td>
                                                              <td><input type="radio" name="switch_choice" value="48port" />48-portowy</td></tr>
			<tr><td><label>Podaj nazwę przełącznika uplinkowego:</label></td><td><input type="text" name="uplink_switch_name"/></td></tr>
			<tr><td><label>Podaj nazwę przełącznika konfigurowanego:</label></td><td><input type="text" name="switch_name"/></td></tr>
			<tr><td><label>Podaj IP nowego przełącznika:</label></td><td><input type="text" name="ip"/></td></tr>
			<tr><td><label>Wybierz rejon:</label></td><td><select name="area">
				         <option>rejOK13ł</option>
							<option>rejOK22g</option>
							<option>rejOK5g</option>
							<option>rejOP11a</option>
							<option>rejOP19e</option>
							<option>rejOP22m</option>
							<option>rejOP3g</option>
							<option>rejOP4j</option>
							<option>rejOP4jv2</option>
							<option>rejOPL13d</option>
							<option>rejOPL5k</option>
							<option>rejOPL7g</option>
							<option>rejOWW11a</option>
							<option>rejOWW23g</option>
							<option>rejOWW26f</option>
							<option>rejOWW34g</option>
							<option>rejOWW6i</option>
							<option>rejOZ12a</option>
							<option>rejOZ19</option>
							<option>rejOZ22ł</option>
							<option>rejOZ4d</option>
			        </select></td></tr>
			<tr><td><label>Wybierz maila:</label></td><td><select name="mail">
						<option>michal.okonski@wachowiakisyn.pl</option>
						<option>sebastian.michalski@wachowiakisyn.pl</option>
					   <option>daniel.mikolajewski@wachowiakisyn.pl</option>
						<option>leszek.piotrowicz@wachowiakisyn.pl</option>
						<option>borys.owczarzak@wachowiakisyn.pl</option>
						<option>pawel.michalek@wachowiakisyn.pl</option>
			</select></td></tr>
			<tr><td></td><td><input type="submit" value="OK"/>  </td></tr>
		</table>
      </form>
      
     
    </body>
</html>


