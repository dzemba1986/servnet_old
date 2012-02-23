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

if(!defined('DADDY_CLASS'))
{
	require(SEU_ABSOLUTE.'/include/classes/daddy.php');
	define('DADDY_CLASS', true);
}
if(!defined('DEVICE_CLASS'))
{
	require(SEU_ABSOLUTE.'/include/classes/device.php');
	define('DEVICE_CLASS', true);
}
if(! defined('LOKALIZACJA'))
{
	require(SEU_ABSOLUTE.'/include/classes/lokalizacja.php');
	define('LOKALIZACJA', true);
}
if(! defined('SWITCH_REJON'))
{
	require(SEU_ABSOLUTE.'/include/classes/switch_rejon.php');
	define('SWITCH_REJON', true);
}
if(! defined('SWITCH_BUD'))
{
	require(SEU_ABSOLUTE.'/include/classes/switch_bud.php');
	define('SWITCH_BUD', true);
}
if(! defined('ROUTER'))
{
	require(SEU_ABSOLUTE.'/include/classes/router.php');
	define('ROUTER', true);
}
if(! defined('SERWER'))
{
	require(SEU_ABSOLUTE.'/include/classes/serwer.php');
	define('SERWER', true);
}
if(! defined('KAMERA'))
{
	require(SEU_ABSOLUTE.'/include/classes/kamera.php');
	define('KAMERA', true);
}
if(! defined('HOST'))
{
	require(SEU_ABSOLUTE.'/include/classes/host.php');
	define('HOST', true);
}
if(! defined('BRAMKA_VOIP'))
{
	require(SEU_ABSOLUTE.'/include/classes/bramka_voip.php');
	define('BRAMKA_VOIP', true);
}
if(! defined('VLAN'))
{
	require(SEU_ABSOLUTE.'/include/classes/vlan.php');
	define('VLAN', true);
}
if(! defined('PODSIEC'))
{
	require(SEU_ABSOLUTE.'/include/classes/podsiec.php');
	define('PODSIEC', true);
}
if(! defined('IP'))
{
	require(SEU_ABSOLUTE.'/include/classes/ip.php');
	define('IP', true);
}
if(! defined('MIESZKANIA'))
{
	require(SEU_ABSOLUTE.'/include/classes/mieszkania.php');
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
