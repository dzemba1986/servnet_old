<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$podsiec = new Podsiec();
$podsiec->usunPodsiec($_GET['id']);
?>

