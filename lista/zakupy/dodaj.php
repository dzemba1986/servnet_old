<?php 
session_set_cookie_params('600');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content=" [dane autora] ">
  <meta name="Generator" content="kED2">
  <link rel="stylesheet" href="styles.css" type="text/css">

  <title>Dodaj/Modyfikuj</title>

<script type="text/javascript">
	function testZglDate(data_zgl)
	{
		var checked = document.getElementById('check_data_zgl');
		var result = data_zgl.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
		if(result)
			checked.src="checked.png";
		else
			checked.src="unchecked.png";
	}
	function testZamDate(data_zam)
	{
		var checked = document.getElementById('check_data_zam');
		var result = data_zam.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
		if(!data_zam || result)
			checked.src="checked.png";
		else
			checked.src="unchecked.png";
	}
	function testOdDate(data_od)
	{
		var checked = document.getElementById('check_data_od');
		var result = data_od.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
		if(!data_od || result)
			checked.src="checked.png";
		else
			checked.src="unchecked.png";
	}
	function check()
	{
		var submit = document.getElementById('zapisz');
	//	alert(document.getElementById('check_data_zgl').src);
		if(document.getElementById('check_data_zgl').src.match(/\/checked.png/) &&
			document.getElementById('check_data_od').src.match(/\/checked.png/) &&
			document.getElementById('check_data_zam').src.match(/\/checked.png/))
			submit.disabled=false;
		else
			submit.disabled=true;
	}

</script>
</head>
<body>
<?php
if($_POST['wyloguj']=="Wyloguj")
{
	session_destroy();
	die("<h1><center>Zostales wylogowany</center><h1>");
}
$ip = $_SERVER['REMOTE_ADDR'];
echo $ip;
if(substr($ip, 0, 5) != "172.2" && $ip != "213.5.210.130" && $ip != "62.21.67.183") 
{
	if($_POST['login']=="wachowiak" && md5($_POST['haslo'])=="e9d58021c1b7d6686bcd1fe62c5bdc29")
	{
		$_SESSION['logged_in'] = "wachowiak";
	}
	elseif($_SESSION['logged_in']!="wachowiak")
	{
		echo"<div style=\"text-align:center\">Podaj login i hasło";
		echo "<form method=\"post\">";
		echo"login <input type=\"text\" name=\"login\"><br>";
		echo"hasło <input type=\"password\" name=\"haslo\"><br>";
		echo" <input type=\"submit\" value=\"Zaloguj\"></form></div>";
		exit();
	}
}
if(isset($_GET['id']))
{
	mysql_connect('localhost', 'internet', 'szczurek20P4');
	mysql_select_db('internet');
	$char = "set character_set_connection='latin2', character_set_client='latin2', character_set_results='latin2'";
	$enc = "set lc_time_names = 'pl_pl'";
	mysql_query($enc) or die(mysql_error());
	mysql_query($char) or die(mysql_error());
	$id = mysql_real_escape_string($_GET['id']);
	$zapytanie = "SELECT id, DATE_FORMAT(data_od, '%d.%m.%y') AS data_od, przedmiot, DATE_FORMAT(data_zgl, '%d.%m.%y') AS data_zgl,
			 DATE_FORMAT(data_zam, '%d.%m.%y') AS data_zam, DATE_FORMAT(last_modyf, '%d.%m.%y %H:%i') as _last_modyfication, opis 
			FROM Zakupy WHERE id='".$id."'";
	$wynik = mysql_query($zapytanie) or die(mysql_error());
	$rekord = mysql_fetch_assoc($wynik);
}
if(substr($ip, 0, 5) != "172.2" && $ip != "213.5.210.130" && $ip != "62.21.67.183") 
	echo "<div style=\"text-align:right\"><form method=\"POST\"><input type=\"submit\" name=\"wyloguj\" value=\"Wyloguj\"></form></div>";
?>
<a href="index.php" style="width:60px; text-align:center">Powrot</a>
<center><div id="title">Edycja przedmiotu</div><br></center>
<form action="index.php" method="POST">
<input type="hidden" name="id" value="<?php echo($rekord['id']); ?>" />
<center><table cellspacing="0" class="dodaj" summary="">
       <tbody>
         <tr>
           <td>Ostatnia modyfikacja</td><td><?php if($rekord['_last_modyfication']) echo($rekord['_last_modyfication']); ?> </td>
	   <td>&nbsp</td>
	   <td>&nbsp</td>
         </tr>
          <tr>
           <td>Data zgłoszenia</td>
	   <td><input type="text" name="data_zgl" id="data_zgl" onkeyup="testZglDate(this.value);check()"  value="<?php if($rekord['data_zgl']) echo($rekord['data_zgl']);  else echo(date("d.m.y"));?>" /></td>
	   <td><img src="unchecked.png" id="check_data_zgl"></td>
	   <td>'dd.mm.rr'</td>
         </tr>
         <tr>
           <td>Przedmiot</td><td><input type="text" size="30" name="przedmiot" id="przedmiot" onkeyup="check();" value="<?php echo($rekord['przedmiot']); ?>"/></td>
         </tr>
         <tr>
           <td>Data zamówienia</td>
	   <td><input type="text" name="data_zam" id="data_zam" onkeyup="testZamDate(this.value);check();" value="<?php if($rekord['data_zam']!='00.00.00') echo($rekord['data_zam']); ?>" /></td>
	   <td><img src="unchecked.png" id="check_data_zam"></td>
	   <td>'dd.mm.rr'</td>
         </tr>
         <tr>
           <td>Data odbioru</td><td><input type="text" name="data_od" id="data_od" onkeyup="testOdDate(this.value);check();" value="<?php if($rekord['data_od']!='00.00.00') echo($rekord['data_od']);?>" /></td>
	   <td><img src="unchecked.png" id="check_data_od"></td>
	   <td>'dd.mm.rr'</td>
	 </tr>
         <tr>
           <td>Opis zamówienia</td><td colspan="3"><textarea name="opis" id="" cols="70" rows="7"><?php echo($rekord['opis']); ?></textarea></td>
         </tr>
         <tr>
           <td>&nbsp</td><td><input type="submit" name="dodaj" id="zapisz" value="zatwierdź" />
	   			</td>
      </tbody>
       </table>
	<input type="hidden" name="timestamp" value="<?php echo(time());?>">
       </center></form>
<script type="text/javascript">
testOdDate(document.getElementById('data_od').value);
testZamDate(document.getElementById('data_zam').value);
testZglDate(document.getElementById('data_zgl').value);
check();
</script>
</body>
</html>
