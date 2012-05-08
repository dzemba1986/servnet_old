<?php 
require('security.php');
//*******************************
//Test uprawnień do odczytu listy
if($_POST['wyloguj']=="Wyloguj")
{
   session_destroy();
   require('include/forms/formularz_zaloguj.php');
   exit();
}
if(($_SESSION['permissions'] & 1)!=1)
{
   echo("<center><h1 style=\"color:white\">Nie masz uprawnien!</h1></center>");
   session_destroy();
   require('include/forms/formularz_zaloguj.php');
   exit();
}
//*******************************
$title;
$sql = new myMysql();
$sql->connect();
$find_phrase = mysql_real_escape_string($_GET['find_phrase']);
$search_field = mysql_real_escape_string($_GET['search_field']);
if($find_phrase && $search_field)
{
  if($search_field=='start_date' ||  $search_field=='last_modyfication' || $search_field=='wire_installation_date' || 
      $search_field=='socket_installation_date' || $search_field=='resignation_date' || $search_field=='service_configuration' ||
      $search_field=='service_activation' || $search_field=='payment_activation')  
  {
    if(!preg_match('/^((2[0-9][0-9][0-9])-((0[1-9])|(1[0-2]))-((0[1-9])|([1-2][0-9])|(3[01])))$/', $find_phrase))
      if(preg_match('/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/', $find_phrase))
      {
        $tmp = preg_split('/\./', $find_phrase);
        $find_phrase = "20".$tmp[2]."-".$tmp[1]."-".$tmp[0];
      }
      else
        die("Niewlasciwy format daty!");
  }
}
$theme = $_SESSION['theme'];
if (!$theme)
  $theme = 'bright';
if(!$tryb)
  $tryb = $_GET['tryb'];
$tryb = mysql_real_escape_string($tryb);
$order = mysql_real_escape_string($_GET['order']);
require('include/classes/paging.php');
$paging = new Paging();
$paging->initialize($_GET['page_number'], $_GET['rows_per_page']);
if(!$tryb)
  $tryb = 'in_progress';
if(substr($tryb, -13)=="installations")
{
  $wynik = $sql->getInstallationsList($tryb, $order, $paging, $find_phrase, $search_field); 
}
  elseif($tryb!='edit' && $tryb!='logout' && $tryb!='editUser' && $tryb!='modyfications' && $tryb!='invoice')
{
  $wynik = $sql->getList3($tryb, $order, $paging, $find_phrase, $search_field);
}
switch($tryb)
{
  case 'edit':
  $result = $sql->getConnectionAddressAndService($_REQUEST['main_id']);
  $address = $result['address'];
  $title = "$address";
  break;
  case 'done':
  $title = 'Podłączeni abonenci';
  break;
  case 'all':
  if($find_phrase){
    $phrase = htmlspecialchars($find_phrase);
    $title = "Wyniki wyszukiwania: '$phrase'";
  }
  else
    $title = 'Wszyscy abonenci (bez rezygnacji)';
  break;
  case 'resignations':
  $title = 'Odłączeni abonenci';
  break;
  case 'done_installations':
  $title = 'Wykonane instalacje';
  break;
  case 'all_installations':
  if($find_phrase){
    $phrase = htmlspecialchars($find_phrase);
    $title = "Wyniki wyszukiwania: '$phrase'";
  }
  else
  $title = 'Wszystkie instalacje';
  break;
  case 'pending_installations':
  $title = 'Rozpoczęte instalacje';
  break;
  case 'editUser':
  $title = 'Edycja danych użytkownika';
  break;
  case 'for_configuration':
  $title = 'Przyłącza do skonfigurowania';
  break;
  case 'modyfications':
  $title = 'Montaże';
  break;
  case 'invoice':
  $title = 'Zestawienia wykonanych instalacji';
  break;
  default:
  $title = 'Do uruchomienia';
  break;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Author" content="kolemp">
  <meta name="generator" content="IM">

<title><?php echo($title); ?></title>

  <link rel="stylesheet" href="css/themes/<?php echo($theme); ?>/styles.css" type="text/css">
  <link rel="stylesheet" href="css/menu.css" type="text/css">
  <?php
  if($tryb=='modyfications')
  echo '<link rel="stylesheet" href="css/modyf.css" type="text/css">';
  ?>
  <script type="text/javascript" src="js/menu.js"></script>
 <script type="text/javascript" src="js/ajax_base.js"></script>
</head>
<body class="my_body">
<?php
define('LIST_MENU', true);
require('include/html/list_menu.php'); 
?>
<?
