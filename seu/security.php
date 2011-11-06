<?php
//Przemysław Koltermann
//All rights reserved

if(!defined('NESTED'))
    define('NESTED', true);
require('path.php');

$session_life_time = 14400; //4 godziny
if(!defined('SESSION'))
{
  ini_set('session.gc_maxlifetime', $session_life_time);
  session_save_path(ROOT.'/sessions/seu');
  session_start();
  define('SESSION', true);
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
	if(isset($_GET['wyloguj']) && $_GET['wyloguj'])
	{
		session_destroy();
		require('formularz_zaloguj.php');
		die();
	}
	elseif(!headers_sent())
	{
//		session_set_cookie_params($session_life_time);
		session_regenerate_id(true); 
	}
}
elseif($_POST['login'] && $_POST['password'])
{
//uzytkownik sie loguje
	require("include/definitions.php");
	$daddy = new Daddy();
	$user = $daddy->getUser($_POST['login'], $_POST['password']);
	if($user == false)
	die("Nieprawidłowy login lub hasło!");
	session_set_cookie_params($session_life_time);
	session_regenerate_id(true);
	$_SESSION['user_login'] = $user['login'];
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['user_imie'] = $user['imie'];
	$_SESSION['user_nazwisko'] = $user['nazwisko'];
	$_SESSION['user_email'] = $user['email'];
	$_SESSION['timestamp'] = time();
}
else
{
	$location = curPageURL();
	require('formularz_zaloguj.php');
	die();
}
