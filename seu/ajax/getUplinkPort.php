<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$ports = $daddy->getUplinkForm($_GET['dev_id']);
header("Content-type:text/xml; charset=utf-8");
echo $ports;
?>

