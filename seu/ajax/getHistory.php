<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$historia = $daddy->getHistory($_GET['dev_id'], $_GET['lokalizacja']);
header("Content-type:text/xml; charset=utf-8");
echo $historia;
?>

