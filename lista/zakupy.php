<?php 
session_set_cookie_params('600');
session_start();
require('formDuplicat.php');
$tryb = $_GET['tryb'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
  <meta name="Author" content="kolemp">
  <meta name="Generator" content="kED2">

  <title> Lista zakupów</title>

  <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body bgcolor="white">
<?php
if($_POST['wyloguj']=="Wyloguj")
{
	session_destroy();
	die("<h1><center>Zostales wylogowany</center><h1>");
}
$ip = $_SERVER['REMOTE_ADDR'];
echo "<div class=\"header\">$ip</div>";
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
mysql_connect('localhost', 'internet', 'szczurek');
mysql_select_db('internet');
$char = "set character_set_connection='latin2', character_set_client='latin2', character_set_results='latin2'";
$enc = "set lc_time_names = 'pl_pl'";
mysql_query($enc) or die(mysql_error());
mysql_query($char) or die(mysql_error());


//dodawanie o ile jest coÂś do dodania
if($_POST['dodaj'])
{
	$data_zgl = preg_split('/\./', mysql_real_escape_string($_POST['data_zgl']));
	$data_zam = preg_split('/\./', mysql_real_escape_string($_POST['data_zam']));
	$data_od = preg_split('/\./', mysql_real_escape_string($_POST['data_od']));
	$id = mysql_real_escape_string($_POST['id']);
	$opis = mysql_real_escape_string($_POST['opis']);
	$przedmiot = mysql_real_escape_string($_POST['przedmiot']);
	if(!$id && $przedmiot)
	{
		$zapytanie = "INSERT INTO Zakupy SET data_zgl='20".$data_zgl['2']."-".$data_zgl[1]."-".$data_zgl[0]."',
		 address='".$address."', phone='".$phone."', switch='".$switch."', wire='".$wire."', mac='".$mac."',service='".$service."',
		 net_connect='".$net_connect."', phone_connect='".$phone_connect."', net_date='20".$net_date[2]."-".$net_date[1]."-".$net_date[0]."',
		 phone_date='20".$phone_date[2]."-".$phone_date[1]."-".$phone_date[0]."', 
		resignation_date='20".$resignation_date[2]."-".$resignation_date[1]."-".$resignation_date[0]."', thursday_date='".$thursday_date."', 
		  friday_date='20".$f_date[2]."-".$f_date[1]."-".$f_date[0]." $f_time:00', info='".$info."', last_modyfication=NOW()";
		$wykonaj = mysql_query($zapytanie) or die(mysql_error());
		if($wykonaj)
			echo "Dodano";
		
	}
	elseif($address)
	{
		$zapytanie = "UPDATE Przylaczenia SET data_zgl='20".$data_zgl['2']."-".$data_zgl[1]."-".$data_zgl[0]."',
		 address='".$address."', phone='".$phone."', switch='".$switch."', wire='".$wire."', mac='".$mac."',service='".$service."',
		 net_connect='".$net_connect."', phone_connect='".$phone_connect."', net_date='20".$net_date[2]."-".$net_date[1]."-".$net_date[0]."',
		 phone_date='20".$phone_date[2]."-".$phone_date[1]."-".$phone_date[0]."', 
		resignation_date='20".$resignation_date[2]."-".$resignation_date[1]."-".$resignation_date[0]."', thursday_date='".$thursday_date."', 
		  friday_date='20".$f_date[2]."-".$f_date[1]."-".$f_date[0]." $f_time:00', info='".$info."', last_modyfication=NOW() WHERE id='$id'";
		$wykonaj = mysql_query($zapytanie) or die(mysql_error());
		if($wykonaj)
			echo "Zaktualizowano";
	}else
		echo "Niekompletne dane!";
}
$order = mysql_real_escape_string($_GET['order']);
if ($order)
{
	if($order =="adres")
	{
		$isnull = "";
		$order = " ORDER BY address ASC";
	}
	elseif($order =="modyf")
	{
		$isnull = "";
		$order = " ORDER BY last_modyfication DESC";
	}
	else
	{
		$isnull = ", UNIX_TIMESTAMP($order)=0 AS isnull ";
		$order = " ORDER BY isnull ASC, $order ASC";
	}
}
else 
{
	$isnull = ", UNIX_TIMESTAMP(data_zgl)=0 AS isnull ";
	$order = " ORDER BY isnull ASC, data_zgl ASC";
}
$zapytanie = " SELECT id, DATE_FORMAT(data_zgl, '%d.%m.%y') AS _data_zgl, UNIX_TIMESTAMP(data_zgl) AS start_timestamp, (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(data_zgl)) AS awaiting_time, address, switch, wire, mac, service, net_connect, phone_connect, DATE_FORMAT(net_date, '%d.%m.%y') AS _net_date, DATE_FORMAT(phone_date, '%d.%m.%y') AS _phone_date, DATE_FORMAT(resignation_date, '%d.%m.%y') AS _resignation_date, phone, DATE_FORMAT(friday_date, '<b>%W</b><br>%d.%m.%y %H:%i') as _friday_date, info, DATE_FORMAT(DATE_ADD(data_zgl,INTERVAL 40 DAY), '%d.%m.%y') AS _end_date  $isnull FROM Przylaczenia $order";
//echo $zapytanie;
$wynik = mysql_query($zapytanie) or die(mysql_error());
if(substr($ip, 0, 5) != "172.2" && $ip != "213.5.210.130" && $ip != "62.21.67.183") 
	echo "<div style=\"text-align:right\"><form method=\"POST\"><input type=\"submit\" name=\"wyloguj\" value=\"Wyloguj\"></form></div>";
?><br>
<a href="index.php?order=modyf" class="invisible">sortuj</a>
<div style="text-align:left;margin-top:2px;">
<table id='legenda'>
<tr><td>Umowy na 30 dni</td><td>Umowy na 40 dni (od 26.08)</td></tr>
<tr bgcolor="#E9993E"><td>minęło >21 dni od podpisania umowy</td><td>minęło >30 dni od podpisania umowy</td></tr>
<tr bgcolor="#EC5223"><td>minęło >30 dni od podpisania umowy</td><td>minęło >40 dni od podpisania umowy</td></tr>
<tr bgcolor="#CBD665"><td>my wszystko zrobiliśmy, wina abonenta</td><td>my wszystko zrobiliśmy, wina abonent</td></tr>
</table>
</div>
<center><div id="title">Lista podłączeń</div><br>
<a href="index.php?tryb=aktywne" class="nieblok">Nieuruchomione</a><a href="index.php?tryb=nieaktywne" class="nieblok">Uruchomione</a><a href="index.php?tryb=wszystkie" class="nieblok">Wszystkie</a></center>
<br>
<center>
<table cellspacing="0" summary="" border="1" align="center" bordercolor="black">
	<tbody>
	 <tr bgcolor="grey">
		 <td></td><td><a href="index.php?order=data_zgl&tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td><td>Deadline</td><td><a href="index.php?order=adres&tryb=<?php echo $tryb; ?>" class="header">Adres</a></td><td>Telefon</td><td>Przełącznik</td><td>Przewód</td><td>MAC</td><td>Usługa</td><td>Int. gniazdko</td><td>Tel. gniazdko</td><td><a href="index.php?order=net_date&tryb=<?php echo $tryb; ?>" class="header">Int. uruchom.</a></td><td><a href="index.php?order=phone_date&tryb=<?php echo $tryb; ?>" class="header">Tel. uruchom.</a></td><td>Rezygnacja</td><td><a href="index.php?order=friday_date&tryb=<?php echo $tryb; ?>" class="header">Montaż</a></td></td><td>Dod. info</td><td>Edytuj</td>
	 </tr>
	 <?php
	 $row=0;
	 while($wiersz = mysql_fetch_array($wynik))
	{
	$rowcolor;
	$wina_abonenta ="#CBD665";
		if($wiersz['_net_date']=='00.00.00')
			$wiersz['_net_date']='';
		if($wiersz['_phone_date']=='00.00.00')
			$wiersz['_phone_date']='';
		if($wiersz['_resignation_date']=='00.00.00')
			$wiersz['_resignation_date']='';
		if($wiersz['_friday_date']=='<b>sobota</b><br>00.00.00 00:00')
			$wiersz['_friday_date']='';
		if(($wiersz['service'] == 'Telefon' && ($wiersz['_phone_date']||$wiersz['_resignation_date'])) ||
			((strlen($wiersz['service'])==12 || strlen($wiersz['service'])==13) && ($wiersz['_net_date']||$wiersz['_resignation_date'])) ||
			(strlen($wiersz['service'])>13 && (($wiersz['_net_date'] && $wiersz['_phone_date'])||$wiersz['_resignation_date'])))
		{
			if(!$tryb || $tryb=='aktywne')
				continue;
			else
				$rowcolor ="#04b404";
			
		}
		else
		{
			if($wiersz['start_timestamp'] < strtotime('2010-08-26'))
			{
				if($tryb=='nieaktywne')
					continue;
				if($wiersz['awaiting_time'] > 3600*24*21 && $wiersz['awaiting_time'] <= 3600*24*30)
					$rowcolor = "#E9993E";
				elseif($wiersz['awaiting_time'] > 2592000)
				{
					if($wiersz['switch'] && $wiersz['net_connect'] && $wiersz['wire'])
						$rowcolor = $wina_abonenta;
					elseif($wiersz['_friday_date'])
						$rowcolor = "#E9993E";
					else
						$rowcolor = "#EC5223";
				}
				elseif($row%2)
					$rowcolor = "#dcde98";
				else
					$rowcolor = "#f2f5a9";
			}
			else
			{
				if($tryb=='nieaktywne')
					continue;
				if($wiersz['awaiting_time'] > 3600*24*30 && $wiersz['awaiting_time'] <= 3600*24*40)
					if($wiersz['switch'] && $wiersz['net_connect'] && $wiersz['wire'])
						$rowcolor = $wina_abonenta;
					else
						$rowcolor = "#E9993E";
				elseif($wiersz['awaiting_time'] > 2592000)
				{
					if($wiersz['switch'] && $wiersz['net_connect'] && $wiersz['wire'])
						$rowcolor = $wina_abonenta;
					elseif($wiersz['_friday_date'])
						$rowcolor = "#E9993E";
					else
						$rowcolor = "#EC5223";
				}
				elseif($row%2)
					$rowcolor = "#dcde98";
				else
					$rowcolor = "#f2f5a9";
			}
		}
		$mac_value;
		if($wiersz['mac'] && substr($ip, 0, 5) == "172.2")
		{
			$mac_plus = preg_replace('/:/', '+', $wiersz['mac']);
			$mac_value = "<a class=\"header\" href=\"http://172.20.4.8/src/index.php?sourceid=3&filter=$mac_plus&search=Search&highlight=\">".$wiersz['mac']."</a>";
		}
		else
			$mac_value = $wiersz['mac'];
		echo"<tr bgcolor=\"$rowcolor\" class=\"row\">
			<td>".($row+1)."</td>	 
			<td>".$wiersz['_data_zgl']."</td>
			<td>".$wiersz['_end_date']."</td>
			<td>".$wiersz['address']."</td>
			<td>".$wiersz['phone']."</td>
			<td>".$wiersz['switch']."</td>
			<td>".$wiersz['wire']."</td>
			<td>$mac_value</td>
			<td>".$wiersz['service']."</td>
			<td>".$wiersz['net_connect']."</td>
			<td>".$wiersz['phone_connect']."</td>
			<td>".$wiersz['_net_date']."</td><td>".$wiersz['_phone_date']."</td>
			<td>".$wiersz['_resignation_date']."</td>
			<td>".$wiersz['_friday_date']."</td>
			<td>".$wiersz['info']."</td><td><a href=\"dodaj.php?id=".$wiersz['id']."\">Edytuj</a></td>
	 </tr>
	 ";
	 $row++;
	}
	 ?>
	</tbody>
	</table>
	<br>
<a href="dodaj.php" style="width:50px">Dodaj</a></center>
</body>
</html>
