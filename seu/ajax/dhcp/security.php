<?php
//Przemysław Koltermann
//All rights reserved

if(!defined('NESTED'))
    define('NESTED', true);

require('path.php');

session_save_path(ROOT.'/sessions/seu/');
session_start();
if(!$_SESSION['user_id'] || !$_SESSION['user_login'] || !$_SESSION['user_imie'] || !$_SESSION['user_nazwisko'])
	die("Nie jesteś zalogowany, najprawdopodobniej wygasła sesja.\nZaloguj się ponownie.");
