<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$podsiec = new Podsiec();
$podsiec->dodajPodsiec($_GET['nowa_podsiec'], $_GET['nowa_maska'], $_GET['vlan'], $_GET['nowy_opis'], $_GET['nowa_dhcp']);
?>
