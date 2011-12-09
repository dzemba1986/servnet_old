<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/podsiec.php');
$subnet = new Podsiec(); 
if($subnet->setGroup($_GET['g_id'], $_GET['s_id']))
  echo "Ustawiono Grupę";
else 
  echo "Nie ustawiono grupy!";
?>
