<?php
//Przemysław Koltermann
//All rights reserved
session_save_path('/home/ftp/www/sessions/home-test');
session_start();
if(!$_SESSION['user_id'] || !$_SESSION['user_login'] || !$_SESSION['user_imie'] || !$_SESSION['user_nazwisko'])
	die("Nie jesteś zalogowany, najprawdopodobniej wygasła sesja.\nZaloguj się ponownie.");
