<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp(); 
if($dhcp->addGroup($_GET['g_name'], $_GET['g_desc']))
  echo "Dodano grupę";
else 
  echo "Nie dodano grupy!";

?>
