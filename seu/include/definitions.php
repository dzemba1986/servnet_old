<?php
//*****************************************************
//Przemysław Koltermann
//wszelkie prawa zastrzeżone
//*****************************************************
//
//$device = array(
//	`mac`,
//	`ip`,
//	`netmask`,
//	`gateway`,
//	`opis`,
//	`device_type`,
//	`parent_port`,
//	`parent_device`,
//	`osiedle`,
//	`blok`,
//	`klatka,
//	`uplink_mc_sn`)
//
//	MAC podajemy w formacie aa:bb:cc:dd:ee:ff
//	port jest liczbą od 1 do 48

//	Operacje (lista możliwych rejestrowanych w historii):
//	- dodawanie,
//	- usuniecie,
//	- zmiana_konfiguracji,
//	- aktualizacja_oprogramowania

//	do czasu zaprojektowania logowania zdefiujemy na stałe użytkownka

//define('DEBUG', true);
define('MYMYSQL_FILE', ROOT.'/lista/include/classes/mysql.php');
define('CONNECTIONS_FILE', ROOT.'/lista/include/classes/connections.php');

if(!defined('DADDY'))
{
	require('daddy.php');
	define('DADDY', true);
}
if(!defined('DEVICE'))
{
	require('device.php');
	define('DEVICE', true);
}
if(! defined('LOKALIZACJA'))
{
	require('lokalizacja.php');
	define('LOKALIZACJA', true);
}
if(! defined('SWITCH_REJON'))
{
	require('switch_rejon.php');
	define('SWITCH_REJON', true);
}
if(! defined('SWITCH_BUD'))
{
	require('switch_bud.php');
	define('SWITCH_BUD', true);
}
if(! defined('ROUTER'))
{
	require('router.php');
	define('ROUTER', true);
}
if(! defined('SERWER'))
{
	require('serwer.php');
	define('SERWER', true);
}
if(! defined('KAMERA'))
{
	require('kamera.php');
	define('KAMERA', true);
}
if(! defined('HOST'))
{
	require('host.php');
	define('HOST', true);
}
if(! defined('BRAMKA_VOIP'))
{
	require('bramka_voip.php');
	define('BRAMKA_VOIP', true);
}
if(! defined('VLAN'))
{
	require('vlan.php');
	define('VLAN', true);
}
if(! defined('PODSIEC'))
{
	require('podsiec.php');
	define('PODSIEC', true);
}
if(! defined('IP'))
{
	require('ip.php');
	define('IP', true);
}
if(! defined('MIESZKANIA'))
{
	require('mieszkania.php');
	define('MIESZKANIA', true);
}
if(! defined('HISTORIA'))
{
	class Historia extends Daddy
	{
		public $lokalizacja;
		public $device;
		public $data;
		public $autor;
		public $operacja;
		public $opis;
	}	
	define('HISTORIA', true);
}
