<?php 
session_set_cookie_params('600');
session_start();
require('formDuplicat.php');
$tryb = $_GET['tryb'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content=" kolemp ">
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
mysql_connect('localhost', 'internet', 'szczurek20P4');
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
	if(!$id && $przedmiot &&  $data_zgl)
	{
		$zapytanie = "INSERT INTO Zakupy SET data_zgl='20".$data_zgl['2']."-".$data_zgl[1]."-".$data_zgl[0]."',
		opis='".$opis."', przedmiot='".$przedmiot."', data_zam='20".$data_zam[2]."-".$data_zam[1]."-".$data_zam[0]."',
		data_od='20".$data_od[2]."-".$data_od[1]."-".$data_od[0]."', last_modyf=NOW()";
		$wykonaj = mysql_query($zapytanie) or die(mysql_error());
		if($wykonaj)
			echo "Dodano";
		 require_once "/usr/share/php/Mail.php";
		 require_once "/usr/share/php/Mail/mime.php";
		 $from = "zaopatrzenie@zia.com.pl";
		 //$to = "przemyslaw.koltermann@wachowiakisyn.pl";
		 $to = "przemyslaw.koltermann@wachowiakisyn.pl, miroslaw.wachowiak@wachowiakisyn.pl";
		 $subject = "[zamówienie] $przedmiot";
		 $data = mysql_real_escape_string($_POST['data_zgl']);
		 $body = "Zamówienie z dnia $data:\nPrzedmiot: \n\t$przedmiot\nOpis:\n\t$opis";
		 $host = "wachowiakisyn.home.pl:587";
		 $username = "zaopatrzenie@zia.com.pl";
		 $password = "h86050373945fu";
		 
		 $headers = array ('From' => $from,
		   'Content-Type' => 'text/plain; charset="iso8859-2"',
		   'To' => $to,
		   'Subject' => $subject);
		 $smtp = Mail::factory('smtp',
		   array ('host' => $host,
		     'auth' => true,
		     'username' => $username,
		     'password' => $password));
		#
		 $mime = new Mail_mime();
		 $mime->setTXTBody($body);
		 $body = $mime->get(array('text_charset' => 'iso8859-2'));
		 $headers = $mime->headers($headers); 
		 $headers['Subject'] = $mime->encodeHeader('Subject', $subject, 'latin2', "base64");
		 $mail = $smtp->send($to, $headers, $body);

		 if (PEAR::isError($mail)) {
		 echo("<p>" . $mail->getMessage() . "</p>");
		 } else {
		 	echo("<p>Message successfully sent!</p>");
		 }
	}
	elseif($przedmiot && $data_zgl)
	{
		$zapytanie = "UPDATE Zakupy SET data_zgl='20".$data_zgl['2']."-".$data_zgl[1]."-".$data_zgl[0]."',
		 opis='".$opis."', przedmiot='".$przedmiot."', data_zam='20".$data_zam[2]."-".$data_zam[1]."-".$data_zam[0]."',
		 data_od='20".$data_od[2]."-".$data_od[1]."-".$data_od[0]."', last_modyf=NOW() WHERE id='$id'";
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
	elseif($order =="data_zgl")
	{
		$isnull = "";
		$order = " ORDER BY data_zgl ASC";
	}
	elseif($order =="modyf")
	{
		$isnull = "";
		$order = " ORDER BY last_modyf DESC";
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
$zapytanie = " SELECT id, DATE_FORMAT(data_zgl, '%d.%m.%y') AS _data_zgl, UNIX_TIMESTAMP(data_zgl) AS zgl_timestamp, opis, przedmiot, DATE_FORMAT(data_zam, '%d.%m.%y') AS _data_zam, DATE_FORMAT(data_od, '%d.%m.%y') AS _data_od $isnull FROM Zakupy $order";
//echo $zapytanie;
$wynik = mysql_query($zapytanie) or die(mysql_error());
if(substr($ip, 0, 5) != "172.2" && $ip != "213.5.210.130" && $ip != "62.21.67.183") 
	echo "<div style=\"text-align:right\"><form method=\"POST\"><input type=\"submit\" name=\"wyloguj\" value=\"Wyloguj\"></form></div>";
?><br>
<a href="index.php?order=modyf" class="invisible">sortuj</a>
<center><div id="title">Lista zakupów</div><br>
<a href="index.php?tryb=zamzgl" class="nieblok">Zgł + Zam</a>
<a href="index.php?tryb=zgl" class="nieblok">Zgłoszone</a>
<a href="index.php?tryb=zam" class="nieblok">Zamówione</a>
<a href="index.php?tryb=od" class="nieblok">Odebrane</a>
</center>
<br>
<center>
<table cellspacing="0" summary="" border="1" align="center" bordercolor="black">
	<tbody>
	 <tr bgcolor="grey">
		<td></td>
		<td><a href="index.php?order=data_zgl&tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td>
		<td><a href="index.php?order=przedmiot&tryb=<?php echo $tryb; ?>" class="header">Przedmiot</a></td>
		<td><a href="index.php?order=data_zam&tryb=<?php echo $tryb; ?>" class="header">Data zamówienia</a></td>
		<td><a href="index.php?order=data_od&tryb=<?php echo $tryb; ?>" class="header">Data odebrania</a></td>
		<td>Opis</td>
		<td>Edytuj</td>
	 </tr>
	 <?php
	 $row=0;
	 while($wiersz = mysql_fetch_array($wynik))
	{
	$rowcolor;
		if($wiersz['_data_zam']=='00.00.00')
			$wiersz['_data_zam']='';
		if($wiersz['_data_od']=='00.00.00')
			$wiersz['_data_od']='';
		if($tryb=="zgl")
		{
			if($wiersz['_data_zam'] || $wiersz['_data_od'])
				continue;
		}
		elseif($tryb=="zam") 
		{
			if(!$wiersz['_data_zam'] || $wiersz['_data_od'])
				continue;
		}
		elseif($tryb=="od")
		{
			if(!$wiersz['_data_zam'] || !$wiersz['_data_od'])
				continue;
		}
		elseif($tryb=="zamzgl")
		{
			if($wiersz['_data_od'])
				continue;
		}
		else
		{
			if($wiersz['_data_od'])
				continue;
		}

/*				elseif($row%2)
					$rowcolor = "#dcde98";
				else
					$rowcolor = "#f2f5a9";
*/		
		if($wiersz['_data_od'])
			$rowcolor='#04b404';
		elseif($wiersz['_data_zam'])
			$rowcolor='#cbd665';
		else
			$rowcolor='#e9993e';

		echo"<tr bgcolor=\"$rowcolor\" class=\"row\">
			<td>".($row+1)."</td>	 
			<td>".$wiersz['_data_zgl']."</td>
			<td>".$wiersz['przedmiot']."</td>
			<td>".$wiersz['_data_zam']."</td>
			<td>".$wiersz['_data_od']."</td>
			<td>".$wiersz['opis']."</td>
			<td><a href=\"dodaj.php?id=".$wiersz['id']."\">Edytuj</a></td>
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
