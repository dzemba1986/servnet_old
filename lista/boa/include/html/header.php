<?php 
require('../security.php');
//*******************************
//Test uprawnień do odczytu listy
if($_POST['wyloguj']=="Wyloguj")
{
   session_destroy();
   require(LISTA_ABSOLUTE.'/include/forms/formularz_zaloguj.php');
   exit();
}
if(($_SESSION['permissions'] & 8)!=8)
{
   echo("<center><h1 style=\"color:white\">Nie masz uprawnien!</h1></center>");
   session_destroy();
   require(LISTA_ABSOLUTE.'/include/forms/formularz_zaloguj.php');
   exit();
}
//*******************************
$title;
$ip = $_SERVER['REMOTE_ADDR'];
$sql = new MysqlBoa();
$sql->connect();
if($_GET['ara_id'])
{ 
    if(($_SESSION['permissions'] & 128) == 128)
{
      $sql->araSync($_GET['ara_id']);
}
}
elseif($_GET['ara_desync_id'])
{ 
    if(($_SESSION['permissions'] & 128) == 128)
      $sql->araDeSync($_GET['ara_desync_id']);
}
elseif($_POST['dodaj'])
{
  if($_SESSION['permissions'] & 4 != 4)
    die("Nie masz uprawnień do dodawania!");
  require(LISTA_ABSOLUTE.'/include/classes/connections.php');
  $connection = new Connections();
  $start_date = $_POST['start_date'];
  $ara_id = $_POST['ara_id'];
  $address = array('ulic' => $_POST['ulic'],
      'blok' => $_POST['blok'],
      'klatka' => $_POST['klatka'],
      'mieszkanie' =>$_POST['mieszkanie'],
      'other_name' =>$_POST['other_name']);
  $mac = $_POST['mac'];
  $service = $_POST['service'];
  $phone = $_POST['phone'];
  $phone2 = $_POST['phone2'];
  $phone3 = $_POST['phone3'];
  $info = $_POST['info_boa'];
  $connection->add($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $ara_id);
}
$tryb = mysql_real_escape_string($_GET['tryb']);
$tryb2 = mysql_real_escape_string($_GET['tryb2']);
$od = mysql_real_escape_string( $_GET['od']);
$do = mysql_real_escape_string( $_GET['do']);
$order =  mysql_real_escape_string(  $_GET['order']);
$payment = intval($_GET['payment']);
$activation = intval( $_GET['activation']);
if(!$tryb)
{
  $tryb = 'search';
  $tryb2 = 'activation';
}
require(LISTA_ABSOLUTE.'/include/classes/paging.php');
$paging = new Paging();
$paging->initialize($_GET['page_number'], $_GET['rows_per_page']);
if($tryb!='add' || $no_res==1)
  $wynik = $sql->getBoaList($tryb, $tryb2, $paging, $od, $do, $order, $payment, $activation);
if($tryb=='add')
  $title = 'Dodaj umowę';
elseif($tryb=='contract' && $tryb2=='all')
  $title = 'Wszystkie umowy';
elseif($tryb=='contract' && $tryb2=='sync')
  $title = 'Zaksięgowane umowy';
elseif($tryb=='contract' && $tryb2=='')
  $title = 'Niezaksięgowane umowy';
elseif($tryb=='search' && $tryb2=='address')
  $title = 'Wyszukiwanie po adresie';
elseif($tryb=='search' && $tryb2=='activation')
  $title = 'Wyszukiwanie po dacie uruchomienia usługi';
elseif($tryb=='search' && $tryb2=='contract')
  $title = 'Wyszukiwanie po dacie podpisania umowy';
else
  $title = 'Lista podłączeń';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Author" content="kolemp">
  <meta name="Generator" content="kED2">

<title><?php echo($title); ?></title>

  <link rel="stylesheet" href="css/styles.css" type="text/css">
  <link rel="stylesheet" href="css/menu.css" type="text/css">
  <script type="text/javascript" src="js/menu.js"></script>
  <script type="text/javascript" src="js/ajax_base.js"></script>
  <script type="text/javascript" src="js/setPhones.js"></script>
  <script type="text/javascript" src="js/setAraId.js"></script>
  <script type="text/javascript" src="js/setInfo.js"></script>
  <script type="text/javascript" src="js/dodaj.js"></script>
</head>
<body>
<div id="main_body">
<?php
define('LIST_MENU', true);
require('include/html/list_menu.php'); 
?>
<?php
