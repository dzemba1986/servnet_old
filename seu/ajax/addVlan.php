<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$vlan = new Vlan();
$vlan->dodajVlan($_GET['vid'], $_GET['opis']);
?>
