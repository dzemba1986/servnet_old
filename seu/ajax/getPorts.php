<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$ports = $daddy->getPortsForm($_GET['dev_id'],$_GET['child']);
header("Content-type:text/xml; charset=utf-8");
echo $ports;
?>

