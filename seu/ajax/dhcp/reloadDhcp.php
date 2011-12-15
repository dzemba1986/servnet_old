<?php require("security.php");
require("path.php");
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp(); 
if($dhcp->reloadDhcp())
  echo "Zaktualizowano serwer dhcp.";
else 
  echo "Nie zaktualizowano serwera dhcp!";
