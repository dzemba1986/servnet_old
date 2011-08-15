<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$vlan = new Vlan();
$vlans = $vlan->getVlans();
header("Content-type:text/xml; charset=utf-8");
echo $vlans;
?>
