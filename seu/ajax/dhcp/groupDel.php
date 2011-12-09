<?php require("security.php");
require("path.php");
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp(); 
if($dhcp->delGroup($_GET['g_id']))
  echo "Usunięto grupę";
else 
  echo "Nie usunięto grupy!";
