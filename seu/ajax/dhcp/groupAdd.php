<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp(); 
$dhcp->addGroup($_GET['g_name'], $_GET['g_desc']);
?>
