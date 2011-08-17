<?php
//Przemysław Koltermann
//All rights reserved

if(!defined('NESTED'))
    define('NESTED', true);

require('path.php');

$session_life_time = 14400; //4 godziny
//$session_life_time = 60; //6 godzin
ini_set('session.gc_maxlifetime', $session_life_time);
$ip = $_SERVER['REMOTE_ADDR'];
if(!defined('SESSION'))
{
  session_save_path(ROOT.'/sessions/lista/');
  session_start();
  define('SESSION', true);
}
if(!defined('MYSQL'))
{
  require(ROOT.'/lista/include/classes/mysql.php');
  define('MYSQL', true);
}
if(!defined('GET_USER'))
{
  function getUser($login, $password)
  {
    $sql = new myMysql();
    $sql->connect();
    $login = mysql_real_escape_string($login);
    $password = mysql_real_escape_string($password);
    $zapytanie = "SELECT * FROM User WHERE login='$login'";
    $wynik = $sql->query_assoc($zapytanie);
    if($wynik['password'] && $wynik['id'])
    {
      $password_hash = sha1(sha1($password).sha1($wynik['id']));
      //			echo"<br>hash: $password_hash<br>z bazy: ".$wynik['password']."<br>";
      if($password_hash==$wynik['password'])
        return $wynik;
    }
    return false;
  }
  define('GET_USER', true);
}
if(!defined('CURPAGEURL'))
{
  define('CURPAGEURL', true);


  function curPageURL()
  {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
  }
}
if($_SESSION['user_id'] && $_SESSION['user_login'] && $_SESSION['user_imie'] && $_SESSION['user_nazwisko'])
{
  //uzytkownik juz zalogowany
  if($_SESSION['user_login']=='monter' && $ip!="46.175.42.130" && $ip!="46.175.42.132")
   die("Nieuprawnione logowanie na konto montera!");
  if($_GET['wyloguj'])
  {
    session_destroy();
    require(ROOT.'/lista/include/forms/formularz_zaloguj.php');
    die();
  }
  elseif(!headers_sent())
  {
    //session_set_cookie_params($session_life_time);
    session_regenerate_id(true); 
  }
}
elseif($_POST['login'] && $_POST['password'])
{
  //uzytkownik sie loguje
  if($_POST['login']=='monter' && $ip!="46.175.42.130" && $ip!="46.175.42.132")
    die("Nieuprawnione logowanie na konto montera!");
  $user = getUser($_POST['login'], $_POST['password']);
  if($user == false)
    die("Nieprawidłowy login lub hasło!");
  session_set_cookie_params($session_life_time);
  session_regenerate_id(true);
  $_SESSION['user_login'] = $user['login'];
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['user_imie'] = $user['imie'];
  $_SESSION['user_nazwisko'] = $user['nazwisko'];
  $_SESSION['user_email'] = $user['email'];
  $_SESSION['permissions'] = $user['permissions'];
  $_SESSION['timestamp'] = time();
  $_SESSION['rows_per_page'] = $user['rows_per_page'];
  $_SESSION['remember_paging'] = $user['remember_paging'];
  $_SESSION['theme'] = $user['theme'];
  $location = curPageURL();
}
else
{
  require(ROOT.'/lista/include/forms/formularz_zaloguj.php');
  die();
}
