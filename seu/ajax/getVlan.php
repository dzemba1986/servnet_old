<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$podsiec = new Podsiec();
$podsiec->connect();
$id = mysql_real_escape_string($_GET['id']);
$vlan = $podsiec->pobierzVlan($id);
echo $vlan;
?>
