<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp_option = new DhcpOption(); 
if($dhcp_option->del($_GET['g_id'], $_GET['s_id'], $_GET['o_id']))
  echo "Usunięto opcję";
else 
  echo "Nie usunięto opcji!";
?>
