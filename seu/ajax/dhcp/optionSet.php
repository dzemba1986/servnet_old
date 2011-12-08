<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp_option = new DhcpOption(); 
if($dhcp_option->set($_GET['g_id'], $_GET['s_id'], $_GET['o_id'], $_GET['o_value'], $_GET['o_weight']))
  echo "Poprawiono opcję";
else 
  echo "Nie poprawiono opcji!";
?>
