<?php
$tryb = 'editUser';
require("include/html/header.php");
$sql = new myMysql();
$sql->connect();
$login = mysql_real_escape_string($_SESSION['user_login']);
$haslo_stare = mysql_real_escape_string($_POST['haslo_stare']);
$haslo1 = mysql_real_escape_string($_POST['haslo1']);
$haslo2 = mysql_real_escape_string($_POST['haslo2']);
$imie = mysql_real_escape_string($_POST['imie']);
$nazwisko = mysql_real_escape_string($_POST['nazwisko']); 
$time = (int)$_POST['time'];
$remember_paging = (int)$_POST['remember_paging'];
$rows_per_page = (int)$_POST['rows_per_page'];
$email = mysql_real_escape_string($_POST['email']);
$theme = mysql_real_escape_string($_POST['theme']);
if($haslo1 != $haslo2)
  die('hasła są różne!');
  //jeżeli zmieniamy hasło
if($login)
{
  $zapytanie = "SELECT * FROM User WHERE login='$login'";
  $wynik = $sql->query($zapytanie);
}
if($login && $haslo1 && $haslo2 && $haslo_stare && $imie && $nazwisko && $email && $theme)
{
  if($login=='monter')
    die("monter nie może sobie zmieniać hasła!");
  $id = $wynik['id'];
  $base_password = $wynik['password'];
  $shashowane = sha1(sha1($haslo_stare).sha1($id));
  if($base_password != $shashowane)
    die("Nie odnaleziono użytkownika lub błędne hasło!");
  $shashowane_nowe = sha1(sha1($haslo1).sha1($id));
  $zapytanie = "UPDATE User SET imie='$imie', nazwisko='$nazwisko', email='$email', password='$shashowane_nowe', rows_per_page='$rows_per_page', remember_paging='$remember_paging', theme='$theme' WHERE id='$id'";
  if($sql->query_update($zapytanie, $id, 'User'))
  {
    echo("<h2>zmodyfikowano użytkownika</h2>");
    $_SESSION['user_login'] = $login;
    $_SESSION['user_imie'] = $imie;
    $_SESSION['user_nazwisko'] = $nazwisko;
    $_SESSION['user_email'] = $email;
    $_SESSION['rows_per_page'] = $rows_per_page;
    $_SESSION['remember_paging'] = $remember_paging;
    $_SESSION['timestamp'] = $time;
    $_SESSION['theme'] = $theme;
    $zapytanie = "SELECT * FROM User WHERE login='$login'";
    $wynik = $sql->query($zapytanie);
  }
  else
    die ("Edycja zakończona niepowodzeniem!");
}
//jeżeli nie zmieniamy hasła
elseif($login && $haslo_stare && !$haslo1 && !$haslo2 && $imie && $nazwisko && $email && $theme)
{
  $zapytanie = "SELECT * FROM User WHERE login='$login'";
  $wynik = $sql->query($zapytanie);
  $id = $wynik['id'];
  $base_password = $wynik['password'];
  $shashowane = sha1(sha1($haslo_stare).sha1($id));
  if($base_password != $shashowane)
    die("Nie odnaleziono użytkownika lub błędne hasło!");
  $zapytanie = "UPDATE User SET imie='$imie', nazwisko='$nazwisko', email='$email', rows_per_page='$rows_per_page', remember_paging='$remember_paging', theme='$theme' WHERE id='$id'";
  if($sql->query_update($zapytanie, $id, 'User'))
  {

    echo("<h2>zmodyfikowano użytkownika</h2>");
    $_SESSION['user_login'] = $login;
    $_SESSION['user_imie'] = $imie;
    $_SESSION['user_nazwisko'] = $nazwisko;
    $_SESSION['user_email'] = $email;
    $_SESSION['rows_per_page'] = $rows_per_page;
    $_SESSION['remember_paging'] = $remember_paging;
    $_SESSION['timestamp'] = $time;
    $_SESSION['theme'] = $theme;
    $zapytanie = "SELECT * FROM User WHERE login='$login'";
    $wynik = $sql->query($zapytanie);
  }
  else
    die ("Edycja zakończona niepowodzeniem!");
}
elseif($_REQUEST['zmien'] && ($login || $haslo_stare || $haslo1 || $haslo2 || $imie || $nazwisko || $email || $theme))
$alert = "Nie wypełniono wszystkich pól!";

?>
<div id="formularz" style="padding-left:100px; padding-top:20px;">
<h2 style="color: red"><?php echo $alert; ?></h2>
<form action="" method="post">
<table cellspacing="0" summary="" style="width: 600px;" class="tables">
<tbody>
<tr>
<td>login*</td><td><?php echo($wynik['login']); ?></td>
</tr>
<tr>
<td>stare hasło*</td><td><input type="password" name="haslo_stare" /></td>
</tr>
<tr>
<td>nowe hasło</td><td><input type="password" name="haslo1" /></td>
</tr>
<tr>
<td>powtórz nowe hasło</td><td><input type="password" name="haslo2"  /></td>
</tr>
<tr>
<td>Imię*</td><td><input type="text" name="imie"  value="<?php echo($wynik['imie']); ?>" /></td>
</tr>
<tr>
<td>Nazwisko*</td><td><input type="text" name="nazwisko"  value="<?php echo($wynik['nazwisko']); ?>" /></td>
</tr>
<tr>
<td>e-mail*</td><td><input type="text" name="email"  size="50" value="<?php echo($wynik['email']); ?>" /></td>
</tr>
<tr>
<td>Schemat kolorów</td><td><select name="theme">
<option value="bright">jasny</option>
<option <?php if($wynik['theme']=='dark') echo "selected";?> value="dark">ciemny</option>
</select>
</td>
</tr>
<tr>
<td>Domyślna liczba wierszy w wynikach</td><td><select name="rows_per_page">
<option>10</option>
<option <?php if($wynik['rows_per_page']==50) echo "selected";?>>50</option>
<option <?php if($wynik['rows_per_page']==100) echo "selected";?>>100</option>
<option <?php if($wynik['rows_per_page']==500) echo "selected";?>>500</option>
<option <?php if($wynik['rows_per_page']==1000) echo "selected";?>>1000</option>
<option <?php if($wynik['rows_per_page']==5000) echo "selected";?>>5000</option>
</select>
</td>
</tr>
<tr>
<td>Pamiętaj ilość rekordów/stronę podczas sesji</td><td><input type="checkbox" name="remember_paging" value="1" <?php if($wynik['remember_paging']) echo "checked" ; ?> /></td>
</tr>
</tbody>
</table>
<input type="submit" value="zmień" name="zmien" style="margin-left: auto; margin-right: auto;"/>
<input type="hidden" name="time"  value="<?php echo(time()); ?>"/>
</form>
</div>
</div>
</body>
</html>

