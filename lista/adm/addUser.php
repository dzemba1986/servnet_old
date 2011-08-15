<?php
require("../include/html/security.php");
if($_SESSION['user_login']!='root')
	die("Nie masz uprawnień do tego pliku!");
$sql = new myMysql();
$sql->connect();
$login = mysql_real_escape_string($_POST['login']);
$haslo1 = mysql_real_escape_string($_POST['haslo1']);
$haslo2 = mysql_real_escape_string($_POST['haslo2']);
$imie = mysql_real_escape_string($_POST['imie']);
$nazwisko = mysql_real_escape_string($_POST['nazwisko']); 
$email = mysql_real_escape_string($_POST['email']);
$privileges =  intval($_POST['privileges']);
if($haslo1 != $haslo2)
	die('hasła są różne!');
$permissions;
switch($privileges)
{
  case 1:
    $permissions = 140;
    break;
  case 2:
    $permissions = 75;
    break;
  case 3:
    $permissions = 3;
    break;
}
if($login && $haslo1 && $haslo2 && $imie && $nazwisko && $email)
{
	$zapytanie = "SELECT * FROM User WHERE login='$login'";
	if($sql->query($zapytanie))
		die("Taki login już jest zajęty");
	$zapytanie = "INSERT INTO User SET login='$login', imie='$imie', nazwisko='$nazwisko', email='$email', permissions='$permissions'";
	if($sql->query($zapytanie))
	{
		$id = mysql_insert_id($sql->sql);
		if($id)
		{
			$shashowane = sha1(sha1($haslo1).sha1($id));
			$zapytanie = "UPDATE User SET password='$shashowane' WHERE login='$login' AND id='$id'";
			if($sql->query($zapytanie))
				echo "Dodano użytkownika $login !";
		}
	}
	else
		die ("Dodawanie zakończone niepowodzeniem");
}
else
	echo "Nie wypełniono wszystkich pól";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content=" [dane autora] ">
  <meta name="Generator" content="kED2">

  <title> Dodawanie konta </title>

  <link rel="stylesheet" href=" [nazwa_arkusza_stylow.css] " type="text/css">
</head>
<body>

	<form action="" method="POST">
	<table cellspacing="0" summary="">
 <tbody>
   <tr>
     <td>login</td><td><input type="text" name="login" /></td>
   </tr>
   <tr>
     <td>hasło</td><td><input type="password" name="haslo1" id="" /></td>
   </tr>
   <tr>
     <td>powtórz hasło</td><td><input type="password" name="haslo2" id="" /></td>
   </tr>
   <tr>
     <td>Imię</td><td><input type="text" name="imie" id="" /></td></td>
   </tr>
   <tr>
     <td>Nazwisko</td><td><input type="text" name="nazwisko" id="" /></td></td>
   </tr>
   <tr>
     <td>e-mail</td><td><input type="text" name="email" id="" /></td></td>
   </tr>
   <tr>
     <td>uprawnienia</td><td><select name="privileges" id=""/>
     <option value='1'>BOA</option>
     <option value='2'>serwis</option>
     <option value='3'>monter</option>
     </select></td></td>
   </tr>
   <tr>
     <td colspan="2"><input type="submit" value="dodaj" /></td>
   </tr>
 </tbody>
 </table>
	</form>
</body>
</html>

