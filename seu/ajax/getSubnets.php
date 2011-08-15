<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$daddy->connect();
$vlan = mysql_real_escape_string($_GET['vlan']);
$children = $daddy->getSubnets($vlan);
header("Content-type:text/xml; charset=utf-8");
echo $children;
?>
