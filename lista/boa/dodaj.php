<?php 
require('include/html/header.php');
$ulic = $sql->getUlic();
?>
<br><br>
<script type="text/javascript" src="js/dodaj.js"></script>
<form class="add_form" action="index.php?tryb=search&tryb2=contract" method="POST">
<input type="hidden" name="id" value="<?php echo($rekord['id']); ?>" />
<center><table cellspacing="0" class="dodaj" summary="">
       <tbody>
         <tr>
           <td>Data zgłoszenia*</td><td><input type="text" name="start_date" id="start_date" onkeyup="check();" value="<?php echo(date("d.m.y")); ?>" /></td>
	   <td>'dd.mm.rr'</td>
         </tr>
         <tr>
           <td>ARA ID*</td><td><input type="text" name="ara_id" onkeyup="check();" id="ara_id"/></td>
	   <td></td>
         </tr>
         <tr>
           <td colspan="4">-----------------------------------------------------------------------------------------------------------</td>
         </tr>
         <tr>
           <td>Ulica*</td><td><select name="ulic" id="ulic" onchange="check();">
           <option></option>
           <?php foreach($ulic as $ulica)
             echo"<option value=\"".$ulica['ULIC']."\">".$ulica['street_name']."</option>";
           ?>
           </select>
           </td>
	   <td></td>
         </tr>
         <tr>
           <td>Blok*</td><td><input type="text" size="10" name="blok" id="blok" onkeyup="check();" value="" /></td>
         </tr>
         <tr>
           <td>Mieszkanie lub Klatka</td><td><input type="text" size="10" name="mieszkanie" id="mieszkanie" onkeyup="check();" value="" /></td>
         </tr>
         <tr>
           <td>Nazwa inna</td><td><input type="text" size="30" name="other_name" id="other_name" value="" onkeyup="check();" /></td>
         </tr>
         <tr>
           <td colspan="4">-----------------------------------------------------------------------------------------------------------</td>
         </tr>
         <tr>
           <td>Telefon</td><td><input type="text" size="30" name="phone" id="phone" value="" onkeyup="check();" /></td>
         </tr>
         <tr>
           <td>Telefon #2</td><td><input type="text" size="30" name="phone2" id="phone2" value="" onkeyup="check();" /></td>
         </tr>
         <tr>
           <td>Telefon #3</td><td><input type="text" size="30" name="phone3" id="phone3" value="" onkeyup="check();" /></td>
         </tr>
         <tr>
           <td>MAC</td><td><input type="text" name="mac" id="mac" onkeyup="check();" /></td>
	   <td>'aa:bb:cc:xx:yy:zz'</td>
         </tr>
         <tr>
           <td>Usługa</td><td><select name="service">	<option >Internet Standard</option>
							<option >Internet Komfort</option>
							<option >Telefon</option>
							<option >Internet Standard + tel</option>
							<option >Internet Komfort + tel</option>
				</select>
				</td>
         </tr>
         <tr>
           <td>Dodatkowe info</td><td colspan="3"><textarea name="info_boa" cols="70" rows="7"></textarea></td>
         </tr>
         <tr>
           <td>&nbsp;</td><td><input type="submit" name="dodaj" id="zapisz" value="zatwierdź" />
	   			</td>
      </tbody>
       </table>
	<input type="hidden" name="timestamp" value="<?php echo(time());?>">
       </center></form>

<script type="text/javascript">
check();
</script>
</div>
</body>
</html>
