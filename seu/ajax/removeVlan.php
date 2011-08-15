<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$vlan = new Vlan();
$vlan->usunVlan($_GET['vid']);
?>

