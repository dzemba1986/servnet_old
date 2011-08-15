<?php
require("security.php");
$sql = new myMysql();
$sql->connect();
$login = mysql_real_escape_string($_POST['login']);
$haslo_stare = mysql_real_escape_string($_POST['haslo_stare']);
$haslo1 = mysql_real_escape_string($_POST['haslo1']);
$haslo2 = mysql_real_escape_string($_POST['haslo2']);
$imie = mysql_real_escape_string($_POST['imie']);
$nazwisko = mysql_real_escape_string($_POST['nazwisko']); 
$time = (int)$_POST['time'];
$email = mysql_real_escape_string($_POST['email']);
if($haslo1 != $haslo2)
	die('hasła są różne!');
//jeżeli zmieniamy hasło
if($login && $haslo1 && $haslo2 && $haslo_stare && $imie && $nazwisko && $email)
{
	$zapytanie = "SELECT id, password FROM User WHERE login='$login'";
	$wynik = $sql->query($zapytanie);
	$id = $wynik['id'];
	$base_password = $wynik['password'];
	$shashowane = sha1(sha1($haslo_stare).sha1($id));
	if($base_password != $shashowane)
		die("Nie odnaleziono użytkownika lub błędne hasło!");
	$shashowane_nowe = sha1(sha1($haslo1).sha1($id));
	$zapytanie = "UPDATE User SET login='$login', imie='$imie', nazwisko='$nazwisko', email='$email', password='$shashowane_nowe' WHERE id='$id'";
	if($sql->query($zapytanie))
	{
		die("zmodyfikowano użytkownika");
		$_SESSION['user_login'] = $login;
		$_SESSION['user_imie'] = $imie;
		$_SESSION['user_nazwisko'] = $nazwisko;
		$_SESSION['user_email'] = $email;
		$_SESSION['timestamp'] = $time;

	}
	else
		die ("Edycja zakończona niepowodzeniem!");
}
//jeżeli nie zmieniamy hasła
elseif($login && $haslo_stare && !$haslo1 && !$haslo2 && $imie && $nazwisko && $email)
{
	$zapytanie = "SELECT id, password FROM User WHERE login='$login'";
	$wynik = $sql->query($zapytanie);
	$id = $wynik['id'];
	$base_password = $wynik['password'];
	$shashowane = sha1(sha1($haslo_stare).sha1($id));
	if($base_password != $shashowane)
		die("Nie odnaleziono użytkownika lub błędne hasło!");
	$zapytanie = "UPDATE User SET login='$login', imie='$imie', nazwisko='$nazwisko', email='$email' WHERE id='$id'";
	if($sql->query($zapytanie))
	{
		
		echo("<h2>zmodyfikowano użytkownika</h2>");
		$_SESSION['user_login'] = $login;
		$_SESSION['user_imie'] = $imie;
		$_SESSION['user_nazwisko'] = $nazwisko;
		$_SESSION['user_email'] = $email;
		$_SESSION['timestamp'] = $time;

	}
	else
		die ("Edycja zakończona niepowodzeniem!");
}
elseif($login || $haslo_stare || $haslo1 || $haslo2 || $imie || $nazwisko || $email)
	echo "<h2>Nie wypełniono wszystkich pól!</h2>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content=" [dane autora] ">
  <meta name="Generator" content="kED2">

  <title> Edycja konta </title>

  <link rel="stylesheet" href="css/styles.css" type="text/css" />
  <link rel="stylesheet" href="css/dodaj.css" type="text/css" />
</head>
<body>
<div id="cialo">
<div id="formularz">

	<form action="" method="POST">
	<table cellspacing="0" summary="">
 <tbody>
   <tr>
     <td>login*</td><td><input type="text" name="login" value="<?php echo($_SESSION['user_login']); ?>" /></td>
   </tr>
   <tr>
     <td>stare hasło*</td><td><input type="password" name="haslo_stare" id="" /></td>
   </tr>
   <tr>
     <td>nowe hasło</td><td><input type="password" name="haslo1" id="" /></td>
   </tr>
   <tr>
     <td>powtórz nowe hasło</td><td><input type="password" name="haslo2" id="" /></td>
   </tr>
   <tr>
     <td>Imię*</td><td><input type="text" name="imie" id="" value="<?php echo($_SESSION['user_imie']); ?>" /></td></td>
   </tr>
   <tr>
     <td>Nazwisko*</td><td><input type="text" name="nazwisko" id="" value="<?php echo($_SESSION['user_nazwisko']); ?>" /></td></td>
   </tr>
   <tr>
     <td>e-mail*</td><td><input type="text" name="email" id="" size="50" value="<?php echo($_SESSION['user_email']); ?>" /></td></td>
   </tr>
   <tr>
     <td>uprawnienia</td><td><input type="text" name="privileges" id="" disabled value="<?php echo($_SESSION['privileges']); ?>"/></td></td>
   </tr>
   <tr>
     <td colspan="2">* - pola obowiązkowe<br>
<input type="submit" value="zmień" /></td>
   </tr>
 </tbody>
 </table>
	<input type="hidden" name="time" id="" value="<?php echo(time()); ?>"/>
	</form>
</div>
<div id="stopka"></div>
</div>
</body>
</html>

